<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <link rel="stylesheet" href="http://192.168.0.50/nsw/admin-panel/assets/fonts/font-awesome/css/font-awesome.min.css">

  <title>Holiday Voucher</title>

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



$attributes = json_decode($booking_details['attributes'],true);

//debug($attributes['convenience_fee']);exit;

$user_attributes = json_decode($booking_details['user_attributes'],true);

// debug($phone);exit();

$departure_date = $enquiry_details['departure_date'] ;

if (!$departure_date) {

  $departure_date = $attributes['departure_date'];

}

$name = get_enum_list ( 'title', $pax_details[0]['pax_title'] ).' '.$pax_details[0]['pax_first_name'].' '.$pax_details[0]['pax_middle_name'].' '.$pax_details[0]['pax_last_name'];

$phone_no = @$user_attributes['pn_country_code'].' '.$user_attributes['mobile'];

$email = $user_attributes['email'];

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

  <table class="table fff" bgcolor="#f5f5f5" style="border-collapse: collapse; background: #f5f5f5; border: 15px solid #fff; font-size: 12px; line-height: 18px; margin: 0 auto; font-family: arial; max-width:900px"

 width="100%" cellpadding="0" cellspacing="0" border="0">

    <tbody>

      <tr>

        <td style="padding:0px;">

          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">

            



                    <tr bgcolor="#f5f5f5">

                            <td colspan="4" style="padding: 5px 9px; color: #333; line-height: normal;" class="logocvr">

                            <br><br>

                            <!-- <span style="margin: 0px; margin-top: 30px; font-size:9px; font-weight: 500;"><?= $domainname?></span><br>

                            <span style="margin: 0px; font-size:9px; color: #333;"><?= $phone?></span><br>

                            <span style="color:#f5aa1c; margin-top: 9px; font-size: 9px; margin-bottom: 0px;"><?=$address?></span> -->
                            <?=strtoupper($data['domainname'])?><br>Phone : <?=$data['phone']?><br>Email : <?=$data['email']?><br>Address : <?=STATIC_ADDRESS;?><br><?=STATIC_COUNTRY;?>

                            </td>

                            <td colspan="4" style="padding:5px 9px;" align="right" class="logocvr"><img

                            style="width: 150px; height: 50px; margin-top: 20px;"

                            src="<?=$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>"

                            alt="" /></td> 

                            </tr>





                            <tr>

                            <td colspan="4" style="padding: 5px 9px; color: #fff; line-height: 5px;" class="logocvr">

                            &nbsp;

                            </td>

                            </tr>



                  <tr>

                  <td colspan="4" style="padding: 5px 14px; color: #374c5d; font-size: 9px; line-height: 17px;" class="logocvr">

                  <span style="padding: 8px 0px; font-size: 11px;">Holiday booking reference number:</span><br>

                  <span style="font-weight: bold; font-size: 14px;"><?=$booking_details['app_reference']?></span>

                  </td>

                  <td bgcolor="#f89e2e" colspan="4" style="padding:5px 0px;" align="right" class="logocvr">

                  <span style="padding:11px 14px;  color: #fff; font-size:11px; line-height: normal; text-align: left;">Please present this voucher to the service provider.</span>

                  </td>

                  </tr>



                  <tr>

                            <td colspan="4" style="padding: 5px 9px; color: #fff; line-height: 8px;" class="logocvr">

                            &nbsp;

                            </td>

                            </tr>



                     <?php 

                     if($booking_details['status'] == 'BOOKING_CONFIRMED')

                     {

                      ?>

                      <tr>

                       <td colspan="8" align="left" class="logocvr" style="padding: 14px 14px; color: #374c5d; margin: 0px; font-size: 13px; line-height: 20px; text-align: left; font-weight: bold; border-bottom: 2px solid #fff;">BOOKING CONFIRMATION</td>

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

           <span style="margin:0px; margin-bottom: 4px; font-weight: bold; font-size: 14px;">Holiday Name: <?=$tours_details['package_name']?></span>

           <br>

            <table cellpadding="0" cellspacing="0" style="color: #374c5d; font-size: 9px; width: 100%;">

        

              <tr>

               <td style="padding: 3px 0px; font-weight: bold;">Booking Date</td> 

               <td style="padding: 3px 5px;"><?=$booking_details['booked_datetime']?>

               </td>

              </tr>

             

             

              <tr>

               <td style="padding: 3px 0px; font-weight: bold;">Departure Date</td> 

               <td style="padding: 3px 5px;"><?=$departure_date?>

               </td>

              </tr>

             

              <tr>

               <td style="padding: 3px 0px; font-weight: bold;">Duration</td> 

               <td style="padding: 3px 5px;"><?php

                      $duration = $tours_details['duration'];

                      if($duration==1)

                      {

$duration = ($duration+1).' D | '.($duration).' N';

                      }

                      else

                      {

$duration = ($duration+1).' D | '.($duration).' N';

                      }

                      ?>

                    <?=$duration?>

               </td>

              </tr>



              <tr>

               <td style="padding: 3px 0px; font-weight: bold;">Passenger Details</td> 

               <td style="padding: 3px 5px;"><?=$name?>

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



            <tr>

            <td colspan="6" style="padding: 8px 14px; border-top:1px solid #fff; font-size: 9px;">

             <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-size:9px; color: #374c5d; ">

              <tbody>

               <tr>

                <td colspan="6" style="font-size:9px; font-weight: bold; line-height: 30px; padding:0px;">Customer Details</td>

               </tr>

                    <?php //debug(($attributes));

                                        $attributes1= json_decode($booking_details['attributes']);

                                       $adult_count=0;$child_count=0;

                                       foreach($attributes1->passenger_type as $pt)

                                       {

                                           //echo $pt;

                                           if($pt==1){$adult_count++;}

                                           elseif($pt==2){$child_count++;}

                                           else{}

                                       }

                                       $last_name=$attributes1->last_name;

                                       $first_name=$attributes1->first_name;

                                       //debug($last_name);exit;

                                       ?>

                                     <tr>

                                       <td style="width:25%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Number of Adult</td>

                                       <td style="width:25%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;"><?php echo $adult_count; ?></td>

                                        <td style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">

                                            <?php

                                             $get_adult=0;

                                               foreach($attributes1->passenger_type as $pt)

                                               {

                                                   if($pt==1)

                                                   {

                                                       $Aname=$first_name[$get_adult];

                                                       $Lname=$last_name[$get_adult];

                                                       echo $Aname.' '.$Lname; ?><br/>

                                                 <?php  }

                                                   

                                                   $get_adult++;

                                               } 

                                            ?>

                                        </td>

                                      

                                    </tr>

                                       <tr>

                                       <td style="width:25%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">Number of Child</td>

                                       <td style="width:25%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;"> <?php echo $child_count; ?>

                                       </td>

                                        <td style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">

                                            <?php

                                             $get_child=0;

                                               foreach($attributes1->passenger_type as $pt)

                                               {

                                                   if($pt==2)

                                                   {

                                                       $Aname=$first_name[$get_child];

                                                       $Lname=$last_name[$get_child];

                                                       echo $Aname.' '.$Lname; ?><br/>

                                                 <?php  }

                                                   

                                                   $get_child++;

                                               } 

                                            ?>

                                        </td>

                                    </tr>

               <tr>

               <!--  <td align="left" style="padding: 5px; width: 30%; font-weight: bold;">Phone Number</td>

                <td align="left" style="padding: 5px; width: 35%; font-weight: bold;">Email ID</td> -->

               

               </tr>

               <tr>

               <!--  <td align="left" style="padding: 5px 0px"><?=$name?></td> -->

            <!--     <td align="left" style="padding: 5px"><?=$phone_no?></td>

                <td align="left" style="padding: 5px"><?=$email?></td> -->

               </tr>

              </tbody>

             </table>

            </td>

           </tr>





          <!--  <tr style="border-bottom:1px solid #fff; border-top:1px solid #fff;">

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

                      if ($attributes['adult_price']) {

                        echo '<span style="margin:0px;">Adult Fare : '. sprintf("%.2f", ceil($attributes['adult_price'])).' '.$booking_details['currency_code'].'</span><br>';

                      }

                      if ($attributes['child_price']) {

                        echo '<span style="margin:0px;">Child Fare : '. sprintf("%.2f", ceil($attributes['child_price'])).' '.$booking_details['currency_code'].'</span><br>';

                      }

                      if ($attributes['infant_price']) {

                        echo '<span style="margin:0px;">Infant Fare : '. sprintf("%.2f", ceil($attributes['infant_price'])).' '.$booking_details['currency_code'].'</span><br>';

                      }

                      if($booking_details['discount']){

                       echo "<p>Promocode Amount: ".$booking_details['discount']." ".$booking_details['currency_code']."</p>";

                      }

                      ?>

                      <?php $final_total = $booking_details['basic_fare']-$booking_details['discount']; ?>

                      <strong><?= $final_total.' '.$booking_details['currency_code']?></strong>

        </td>

       </tr>

      </tbody>

     </table>

    </td>

   </tr> -->



         

      <tr>

        <td colspan="6" width="100%">



                <table width="100%" border="0" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">

                  <tr>

                    <td style="text-align:left; font-size:9px; color:#374c5d; font-weight: bold;">Itinerary Details                        

                    </td>

                  </tr>

                </table>

              </td>

            </tr>

            <tr>

              <td width="100%">

                <table width="100%"  border="0" cellpadding="6" cellspacing="5" style="border-collapse: collapse; font-size: 9px; color: #374c5d;">

                  <?php

      foreach ($tours_itinerary_dw as $key => $itinary) {

        $accommodation = $itinary['accomodation'];

        $accommodation = json_decode($accommodation);

        ?>

          <tr>

            <td width="10%" align="left" style="color: #374c5d;border:solid 1px #fff; font-weight:bold">Day <?php echo $key+1; ?></td>

            <td width="20%" style="border:solid 1px #fff"><strong>Visiting Place:</strong>

              <p style="margin-top:0; margin-bottom:5px;"><?php echo  $itinary['program_title']; ?></p>

            </td>

            <td width="20%" style="border:solid 1px #fff"><strong>Night Stay:</strong>

              <p style="margin-top:0; margin-bottom:5px;">

                <?php echo  $itinary['program_title']; ?>                      

              </p>

            </td>

            <td width="50%" style="border:solid 1px #fff"><strong>Description:</strong>

            <span style="margin:0;"><?php echo  $itinary['program_des'];   ?></span><br>

          <!--  <span style="margin:0;">Overnight at hotel <?=$itinary['hotel_name']?></span><br>-->

            <span>

              <?php 

              if($itinary['rating'])

              {

                ?>

                <img src="http://www.Tripmia.com/extras/custom/keWD7SNXhVwQmNRymfGN/images/star_rating_<?=$itinary['rating']?>.png" alt="">

                <?php

              }

              ?>

            </span><br>

            <?php 

            $meals = json_decode($itinary['accomodation'],true);

            $meals = implode(' | ',$meals);

            ?>

           <!-- <strong>Meal Plan: <?=$meals; ?></strong>-->

            </td>

          </tr>

          <?php

        }

        ?>



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

    <td>

      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 9px; color: #374c5d; ">

          <tr>

            <td style="text-align:left; font-size:9px; color:#374c5d; font-weight: bold;border-bottom:1px solid #ffffff;line-height: 30px;">Purchase Summary(<?php echo $booking_details['currency_code']; ?>)

            </td>

            </tr>

      </table>

    </td>

  </tr>

  <tr>

    <td style="line-height: 5px;">&nbsp;</td>

  </tr>

  <tr>

    <td>

        <table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-collapse: collapse; font-size: 9px; color: #374c5d; ">

            <tbody>

                <?php 

               

                  $base_fare = $booking_details['fare'];

                 // $currency_obj = new Currency(array('module_type' => 'holiday', 'from' => $booking_details['currency'], 'to' =>$CurrencyCode));

                    $currency_obj = new Currency(array('module_type' => 'Holiday','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));

  

                  $base_fare = $currency_obj->get_currency ( $base_fare, true, false, true);

                  $base_fare = sprintf("%.2f", $base_fare['default_value']);

                ?>

                  <?php

                  $convinence_amount = $currency_obj->get_currency ( $booking_details['convinence_amount'], true, false, true);

                  

                  $convinence_amount = $convinence_amount['default_value'];



                  $base_fare=0.00;

                  if(isset($booking_details['basic_fare']))

                  {

                      $base_fare=($booking_details['basic_fare']);

                      $base_fare=str_replace(",", "", $base_fare);

                  }



                   $markup=0.00;

                  if(isset($booking_details['markup']))

                  {

                      $markup=number_format($booking_details['markup'], 2);

                      $markup=str_replace(",", "", $markup);

                  }



                  $gst_value=0.00;

                  if(isset($booking_details['gst_value']))

                  {

                      $gst_value=number_format($booking_details['gst_value'], 2);

                      $gst_value=str_replace(",", "", $gst_value);

                  }

                  

                   $discount_value=0.00;

                  if(isset($booking_details['discount']))

                  {

                      $discount_value=number_format($booking_details['discount'], 2);

                      $discount_value=str_replace(",", "", $discount_value);

                  }

                  $gst_value_conv=0;

                    // debug($attributes->convenience_fee);

                  if($attributes['convenience_fee'] > 0 )

                  {

                          $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));

                          // debug($gst_details);exit;

                          if($gst_details['status'] == true){

                              if($gst_details['data'][0]['gst'] > 0){

                                 

                                 

                                 $gst_value_conv = ($attributes['convenience_fee']/100) * $gst_details['data'][0]['gst'];

                              }

                          }

                  }

                  //debug($discount_value);exit;



                  //$total_fare=$base_fare;

                    //$total_fare=$base_fare-$discount_value;

                    $total_fare=$base_fare-$discount_value+$attributes['convenience_fee']+$gst_value_conv-$booking_details['reward_amount'];

                      $total_fare=number_format($total_fare, 2);

                    

                

                 // $base_fare=$base_fare-$gst_value;

                    $base_fare=number_format($base_fare, 2);

                  ?>

                   <tr>

                    

                     <td ><strong>Total price</strong></td>

                       <td >

                           <?php

                            echo $base_fare;

                            ?>

                        </td>

                    </tr>

                

                    <tr>

                      <td ><strong>Taxes & Service Fee</strong></td>

                       <td >

                           <?php

                             echo number_format( $attributes['convenience_fee'],2);

                          

                            ?>

                        </td>

                    </tr>

                    <?php if($gst_value_conv >0){?>

                    <tr>

                      <td ><strong>Taxes & Service Fee</strong></td>

                       <td >

                           <?php

                             echo number_format( $gst_value_conv,2);

                          

                            ?>

                        </td>

                    </tr> 

                  <?php } ?>

                    

                     <tr>

                    

                     <td ><strong>Discount</strong></td>

                       <td >

                           <?php

                             echo $rs=number_format($discount_value+ $booking_details['reward_amount'],2);;

                            ?>

                        </td>

                    </tr>

                   

                         

                      

                          <tr>

                    

                     <td ><strong>Grand Total </strong></td>

                       <td >

                           <?php

                             echo $total_fare ;

                            ?>

                        </td>

                    </tr>

          

                

            </tbody>

          

          

      </table>

    </td>

  </tr>

   <tr>

    <td style="line-height: 5px;">&nbsp;</td>

  </tr>

  <tr>

    <td>

      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 9px; color: #374c5d; ">

         <tr>

          <td style="text-align:left; font-size:9px; color:#374c5d; font-weight: bold;">Services Prepaid By Supplier</td>

        </tr>

      </table>

</td>

</tr>

<tr>

    <td style="line-height: 5px;">&nbsp;</td>

  </tr>

<tr>

  <td>

      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 9px; color: #374c5d; ">

     

      <?php if($tours_details['inclusions']){ ?>

        <tr>

          <td style="text-align:left; font-size:9px; color:#374c5d;font-weight: bold; padding: 0px 14px;border-bottom: 1px solid #ffffff;line-height: 30px;">Holiday Inclusions</td></tr>

            <tr>

    <td style="line-height: 5px;">&nbsp;</td>

  </tr>

            <tr><td style="text-align:left; font-size:9px; color:#374c5d; padding: 0px 14px;"><?php echo htmlspecialchars_decode($tours_details['inclusions']);?></td>

        </tr>

        <?php } ?>

        <tr>

    <td style="line-height: 5px;">&nbsp;</td>

  </tr>

        <tr>

           <td style="text-align:left; font-size:9px; padding: 0px 14px; color:#374c5d;font-weight: bold;border-bottom: 1px solid #ffffff;line-height: 30px;">Holiday Exclusions</td>

          </tr>

          <tr>

    <td style="line-height: 5px;">&nbsp;</td>

  </tr>

            <tr>

              <td style="text-align:left; font-size:9px; color:#374c5d; padding: 0px 14px;"><?php echo htmlspecialchars_decode($tours_details['exclusions']);?>

          </td>

        </tr>

        

     

    

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

        

       <tr style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">
            <td  align="left" class="logocvr">
              <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">

                    Note</h4>

                </td>

            </tr>
            <tr>

                <td width="100%" style="border: 1px solid #cccccc;">

                    <table width="100%" cellpadding="5" style="padding: 10px 20px;font-size: 13px;">

                        <tbody>

                            <tr>

                                <td> <?php echo $data['note']; ?></td>

                            </tr>

                        </tbody>

                    </table>

                </td>

            </tr>
            <tr>

                <td>&nbsp;</td>

            </tr>

 <tr>

    <td style="line-height: 5px;">&nbsp;</td>

  </tr>

        <tr style="border-bottom:1px solid #fff; color:#374c5d;">

                  <td colspan="7" width="100%" style="text-align:left; font-size:9px; padding: 0px 14px; color:#374c5d;font-weight: bold;border-bottom: 1px solid #ffffff;line-height: 30px;">Contact Information

                     </td>

                   </tr>

                   <tr>

                    <td width="100%" colspan="7">

                     <table cellpadding="0" cellspacing="0" width="100%">

                     <tbody>

                  <tr>

                     <td style="text-align:left; color: #374c5d; font-size: 9px;border-collapse: collapse;padding:0px;">For any questions or inquiries please email us at <?=$data['voucher_email']?>. Address:<?=$data['address']?> or contact our 24/7 Help Desk at <span style="color:#ff9a03; "><?=$data['voucher_phone']?></span> our dedicated and well experienced staff will assist you all the way.</td>

                  </tr>



                                    

                  <tr>

                   <!--  <td style="text-align:left; color: #374c5d; font-size: 9px;border-collapse: collapse;padding:0px;">We at WiseTravel are here to make your travel worry free. So zip freely, hop often and remember to always enjoy the journey!   </td>

                 --> </tr>

                </tbody>

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



</body>

</html>