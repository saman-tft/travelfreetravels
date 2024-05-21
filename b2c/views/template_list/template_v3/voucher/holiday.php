<div id="printpage">

    <style>
    .page-break {
        clear: both;
        margin-bottom: 20px;
    }

    .print_btn_area {
        text-align: center;
        margin-bottom: 20px;
    }

    th,
    td {
        padding: 5px;
    }

    @media print {

        .page-break {
            page-break-after: always;
        }

        .main-footer,
        .main-header,
        .navbar,
        .main-sidebar,
        .print_btn_area {
            display: none;
        }

        table {
            width: 100%;
        }

        p {
            margin-bottom: 5px;
        }

        img {
            display: block;
            opacity: 1;
            z-index: 99999;
        }

    }

    .table>tbody>tr>td {
        text-align: left;
    }
    </style>

    <?php

//debug($booking_details);exit();

   $app_reference = $booking_details['app_reference'];

   // debug($holiday_booking_user_details[0]);

   // $enquiry_reference_no = $booking_details['enquiry_reference_no'];

   // debug($enquiry_reference_no);

   //$booking_details = $data ['booking_details'] [0];

   $cncel_policy = @$booking_details['cancellation_details'];

   $cancel_policies = @$booking_details['cancellation_policy'];

  ///  echo debug($booking_details);

    $booking_response = json_decode($booking_details['attributes'],1);

   // exit;

   $itinerary_details = $booking_details ['itinerary_details'] [0];

   $itinerary_mul_room = $booking_details ['itinerary_details'];

   $room_type = array();

   foreach ($itinerary_mul_room as $key => $value) {

    $bed_type_code .= $value['room_type_name'].'/';

    array_push($room_type, $value['room_type_name']);

   

   }

   // $room_type = array_unique($room_type);

   // $room_type = implode('/', $room_type);

    $attributes = json_decode ( $booking_details ['attributes'] );

   // $rate_comment = json_decode(base64_decode($attributes->base_en_rate),true);

  // debug($vpu_details);exit;

    //debug($attributes->convenience_fee);exit;

 //   debug($booking_details);exit();

   $customer_details = $pax_details;

   // debug($customer_details);

    $pax_name = "";

       $pax_age = "";

       $pax_age_arr = array();

       $pax_room_arr = array();

       $pax_adult_arr = array();

   

       for ($ps=0; $ps < count($customer_details) ; $ps++) {

   

         $pax_room_arr[$customer_details[$ps]['rooms']] = $customer_details[$ps]['rooms'];

           $pax_adult_arr[$customer_details[$ps]['rooms']][] = $customer_details[$ps]['pax_type'];

   

         if($ps >= 1){

   

           $pax_name .= $customer_details[$ps]['title'].' '.$customer_details[$ps]['first_name'].' '.$customer_details[$ps]['last_name'].',';

           if($customer_details[$ps]['pax_type'] == 'Child'){

             $birthDate = date("d/m/Y", strtotime($customer_details[$ps]['date_of_birth']));

             $birthDate = explode("/", $birthDate);

              // debug($birthDate);

              // debug(date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))));//0104

              // debug(date("md"));//0129

               $age = ($birthDate[2]) > date("Y")? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]);

   

               // debug($age);

               if(isset($age)){

                if($age==0){

   

                  $pax_age_arr[$customer_details[$ps]['rooms']][] =  'Less than Year' ;

                }else{

                  $pax_age_arr[$customer_details[$ps]['rooms']][] =  $age ;

                }

               }

           }

         }

       } 

       // debug($pax_age_arr);exit;

       $rooms=0;

       foreach ($customer_details as $key => $value) {

        if($rooms!=$customer_details[$key]['rooms']){

          $lead_pax[$rooms]['pax_name']=$customer_details[$key]['title'].' '.$customer_details[$key]['first_name'].' '.$customer_details[$key]['last_name'];

          $lead_pax[$rooms]['rooms']=$customer_details[$key]['rooms'];

          $rooms=$customer_details[$key]['rooms'];

        }

       }

       

       $new_pax_and_room_arr = array();

   

       foreach ($pax_room_arr as $key => $value) {

   

         $itinerary_rooms = $key - 1;

         $new_pax_and_room_arr[$key]['pax_type'] = array_count_values($pax_adult_arr[$key]);

         $new_pax_and_room_arr[$key]['pax_type'] = array_count_values($pax_adult_arr[$key]);

         $new_pax_and_room_arr[$key]['room_type_name'] = $itinerary_mul_room[$itinerary_rooms]['room_type_name']; 

         $new_pax_and_room_arr[$key]['total_fare'] =  sprintf("%.2f",$itinerary_mul_room[$itinerary_rooms]['total_fare']); 

         $new_pax_and_room_arr[$key]['child_ages'] = $pax_age_arr[$key];

         $new_pax_and_room_arr[$key]['child_ages'] = $pax_age_arr[$key];

         $new_pax_and_room_arr[$key]['currency'] = $itinerary_mul_room[$itinerary_rooms]['currency'];

         if(!empty($itinerary_mul_room[$itinerary_rooms]['attributes'])){

           $attributes_pax = json_decode ( $itinerary_mul_room[$itinerary_rooms]['attributes'] );

           $new_pax_and_room_arr[$key]['boardCode'] = $attributes_pax->rates->boardName;

         }else{

           $new_pax_and_room_arr[$key]['boardCode'] = 'N/A';

         } 

       } 

   //debug($pax_age_arr);exit;

   $domain_details = $booking_details;

   $lead_pax_details = $booking_details ['customer_details'];

   $CurrencyCode = $attributes->user_selected_currency;



       

   

   ?>

    <div style="clear:both"></div>

    <div class="print_btn_area"
        style="width: 100%; max-width: 900px;clear:both; margin: 10px auto;margin-top: 0;padding-top: 12px;">

        <div style="display: none; margin: 3px 10px;">

            <div class="squaredThree">

                <input id="btn-togglr-address" type="checkbox" name="tc"> <label for="btn-togglr-address"></label>

            </div>

            <label for="btn-togglr-address"> Holiday Voucher Booking : <?php echo $app_reference; ?> Booked on
                :<?php echo date("d M Y",strtotime($booking_details['created_datetime'])); ?></label>

        </div>

        <div style="display: none; margin: 3px 10px;">

            <div class="squaredThree">

                <input id="btn-togglr-fare" type="checkbox" name="tc"> <label for="btn-togglr-fare"></label>

            </div>

            <!-- <label for="btn-togglr-fare"> Hide Fare </label> -->

        </div>

        <?php if($email_status!=1) { ?>

        <button class="btn-sm btn-primary print"
            onclick="w=window.open();w.document.write(document.getElementById('tickect_hotel').innerHTML);w.print();w.close(); return true;">Print</button>

        <?php } ?>



        <?php if($email_status!=1) { ?>

        <div class="well max_wdth e_maill">

            <h4>Send Email</h4>

            <form name="agent_email" method="post"
                action="<?php echo base_url () . 'index.php/voucher/hotel/'.$booking_details['app_reference'].'/'.$booking_details['booking_source'].'/'.$booking_details['status'].'/email_voucher';?>">

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

        <?php } ?>



    </div>

</div>

<div class="table-responsive" id="tickect_hotel">

    <table class="table holivoch"
        style="font-size:12px; font-family: 'Open Sans', sans-serif; width:850px; box-shadow:0 0 5px 0 rgba(0,0,0,.11); margin:30px auto; border-radius:0px!important;background-color:#fff; padding:20px;border-collapse:separate; color: #000;"
        width="100%" cellpadding="0" cellspacing="0" border="0">

        <tbody>

            <tr style="display: none">

                <td bgcolor="#ffffff" align="center" style="-webkit-text-size-adjust: none;border-collapse: collapse">

                    <table width="900" cellspacing="0" cellpadding="0" border="0"
                        style="border-collapse: collapse;mso-table-lspace: 0;mso-table-rspace: 0">

                        <tbody>

                            <tr>

                                <td
                                    style="padding: 0 0px 0 0px;-webkit-text-size-adjust: none;border-collapse: collapse">

                                    <table width="100%;"
                                        style="border-collapse: collapse;mso-table-lspace: 0;mso-table-rspace: 0">

                                        <tbody>

                                            <tr>

                                                <td
                                                    style="background: #739417;background-color: #739417;color: #fff;display: block;font-size: 14px;text-align: center;overflow: hidden;padding: 10px 10px 10px 10px;-webkit-text-size-adjust: none;text-transform:uppercase;border-collapse: collapse;-webkit-print-color-adjust:exact;">
                                                    Please present this voucher to the local representative </td>

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

                <td style="border-collapse: collapse; ">

                    <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0" border="0">

                        <tr>

                            <td>

                                <table cellpadding="0" cellspacing="0" border="0" width="100%"
                                    style="border-collapse: collapse;">

                                    <tr>



                                        <td colspan="1" style="padding:5px 10px;" align="left" class="logocvr">



                                            <!--   <img style="-webkit-print-color-adjust: exact;max-width: 265px; height: 100px;" src="<?=$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>" alt="" />

                        -->

                                            <?php

                                

                                 if($email_status!=1) 

                                 { ?>

                                            <img style="-webkit-print-color-adjust: exact;width: 130px;"
                                                src="<?=$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo())?>"
                                                alt="" />

                                            <?php }

                                   else

                                   {  ?>

                                            <img style="-webkit-print-color-adjust: exact;width: 130px;"
                                                src="<?php echo base_url()."../../".$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo());?>"
                                                alt="" />

                                            <?php } ?>





                                        </td>

                                        <!-- <td colspan="4" style="padding: 5px 14px;line-height: 17px;" class="logocvr">

                                 <?=$data['domainname']?>

                                 <br>Email Id : <?= $this->entity_domain_mail ?>

                                 <br><?=$data['address']?>

                              </td> -->

                                        <td colspan="4" align="right"
                                            style="padding-top:10px;font-size:13px;line-height:20px;">
                                            <?=strtoupper($data['domainname'])?><br>Phone : <?=$data['phone']?><br>Email
                                            : <?=$data['email']?><br>Address :
                                            <?=STATIC_ADDRESS;?><br><?=STATIC_COUNTRY;?></td>

                                    </tr>

                                    <tr>

                                        <td colspan="4" style="padding: 5px 10px; color: #fff; line-height: 17px;"
                                            class="logocvr">

                                            &nbsp;

                                        </td>

                                    </tr>

                                    <tr style="border-top: 2px solid #85c7e0;    border-bottom: 2px solid #85c7e0;">

                                        <td>
                                            <h4
                                                style=" color: #374c5d; font-size: 16px; margin: 0px; line-height: 24px; text-align: left; font-weight: bold;">
                                                Tour Name: <?php echo $tours_details['package_name']; ?></h4>
                                        </td>

                                    </tr>

                                    <tr>

                                        <td colspan="4"
                                            style="padding: 5px 14px; width: 65%; color: #374c5d; line-height: 17px;text-align: left;"
                                            class="logocvr">

                                            <span style="padding: 8px 0px; font-size: 14px;">Holiday booking reference
                                                number:</span>

                                            <h4 style="font-weight: bold; font-size: 17px;">
                                                <?php echo $app_reference; ?></h4>

                                        </td>

                                        <td colspan="4" style="padding:5px 0px;" align="right" class="logocvr">

                                            <h4
                                                style="padding:11px 14px; background: #8f53a1;-webkit-print-color-adjust: exact; color: #fff; margin-top: 20px; line-height: 20px; text-align: left;font-size:15px;">
                                                Please present this voucher to the service provider.</h4>

                                        </td>

                                    </tr>

                                </table>

                            </td>

                        </tr>

                        <tr>

                            <td>

                                <table width="100%" style="border-collapse: collapse;" cellpadding="0" cellspacing="0"
                                    border="0">

                                    <tr>

                                        <td>

                                            <?php 

                                    if($booking_details['status'] =="BOOKING_CONFIRMED"){

                                    ?>

                                    <tr
                                        style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">

                                        <td colspan="" align="left" class="logocvr">

                                            <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">BOOKING CONFIRMATION</h4>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td
                                            style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                            Tour Voucher</td>

                                    </tr>

                                    <tr>

                                        <td style="border: 1px solid #cccccc;">

                                            <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">

                                                <tbody>

                                                    <tr>

                                                        <td
                                                            style="font-size:13px; font-weight:normal;line-height:18px;">

                                                            <span style="font-size: 14px;">Booking <?php

                                                switch ($booking_details ['status']) {

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

                                                

                                                ?> and guaranteed</span>

                                                        </td>

                                                    </tr>

                                                </tbody>

                                            </table>

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

                                    <tr>

                                        <td>&nbsp;</td>

                                    </tr>

                                    <tr
                                        style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">
                                        <td align="left" class="logocvr">
                                            <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">Lead Passenger Details</h4>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td colspan="6"
                                            style=";margin-top: 0px;width: 100%;font-size: 14px;color: #374c5d;line-height: 20px;padding: 0">

                                            <h3
                                                style="margin:0px; margin-bottom: 0px; font-weight: bold; font-size: 21px;">
                                                <?php echo $booking_details['hotel_name']; ?></h3>

                                            <table style="color: #374c5d; font-size: 13px; width: 100%;">

                                                <tr>

                                                    <!-- <tr><td>&nbsp;</td></tr> -->

                                                <tr>

                                                    <td
                                                        style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                                        Lead guest</td>

                                                    <td
                                                        style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">

                                                        <?php 

                                             // debug($pax_details);exit(); 

                                             if (!empty($holiday_booking_user_details[0]->name)) {

                                              echo $holiday_booking_user_details[0]->name;

                                             }

                                             

                                             $customer_details = $pax_details;

                                             $title = get_enum_list('title',$customer_details[0]['pax_title']);

                                             

                                             echo $title.' '.$customer_details[0]['pax_first_name'].' '.$customer_details[0]['pax_last_name']

                                                                              ?>

                                                    </td>

                                                </tr>

                                                <!-- <?php

                                       if(count($customer_details)>1)

                                       {

                                       ?>

                                       <tr>

                                                                  <td style="padding: 3px 0px; font-weight: bold;">Other guest(s)</td> 

                                                                 

                                       <td style="padding: 3px 0px;">

                                                                      <?php 

                                          for ($ps=0; $ps < count($customer_details) ; $ps++) { 

                                          

                                            if($ps >=1){

                                              $title = get_enum_list('title',$customer_details[0]['pax_title']);

                                              $temp_pax[] = $title.' '.$customer_details[$ps]['pax_first_name'].' '.$customer_details[$ps]['pax_last_name'];

                                            }

                                          } 

                                          echo implode(', ', $temp_pax);

                                          ?>

                                       

                                       </td>

                                       

                                       </tr>  

                                       <?php

                                          }

                                             ?> -->

                                                <tr>

                                                    <td
                                                        style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                                        Booking Date</td>

                                                    <?php //debug($tours_details); ?>

                                                    <td
                                                        style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                                        <?php echo date("d M Y",strtotime($booking_details['created_datetime'])); ?>

                                                    </td>

                                                </tr>

                                                <tr>

                                                    <td
                                                        style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                                        Tour Booked Date</td>

                                                    <?php //debug($tours_details); ?>

                                                    <td
                                                        style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                                        <?php echo (($booking_details['departure_date'])); ?>

                                                    </td>

                                                </tr>

                                                <tr>

                                                    <td
                                                        style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                                        Duration</td>

                                                    <td
                                                        style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;"><?php

                                          $duration = $tours_details['duration'];

                                          if($duration==1)

                                          {

                                            $duration = ($duration+1).' Day | '.($duration).' Night';

                                          }

                                          else

                                          {

                                            $duration = ($duration+1).' Day | '.($duration).' Night';

                                          }

                                          ?>

                                                        <?=$duration?>

                                                    </td>

                                                </tr>

                                                <tr>

                                                    <td>&nbsp;</td>

                                                </tr>

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

                                            <table width="100%" cellpadding="5" style="padding: 10px; font-size: 13px;">

                                                <tr>

                                                    <td
                                                        style="padding: 8px 5px; background: #eee;-webkit-print-color-adjust: exact;">
                                                        <strong>Passenger Name</strong>
                                                    </td>

                                                    <td
                                                        style="padding: 8px 5px; background: #eee;-webkit-print-color-adjust: exact;">
                                                        <strong>Pax Type</strong>
                                                    </td>

                                                    <td
                                                        style="padding: 8px 5px; background: #eee;-webkit-print-color-adjust: exact;">
                                                        <strong>Age</strong>
                                                    </td>

                                                </tr>

                                                <?php  // debug($customer_details);

                                       //$pscount = count($customer_details) - 1;

                                        for ($ps=0; $ps < count($customer_details) ; $ps++) { 

                                       ?>

                                                <tr>

                                                    <td style="padding: 8px 5px;">
                                                        <?php echo $customer_details[$ps]['title'].' '.$customer_details[$ps]['first_name'].' '.$customer_details[$ps]['last_name'];?>
                                                    </td>

                                                    <td style="padding: 8px 5px;">
                                                        <?php echo $customer_details[$ps]['pax_type'];?></td>

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

                                    <table width="100%" border="0" cellpadding="6" cellspacing="0"
                                        style="border-collapse: collapse;">

                                        <tr
                                            style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">

                                            <td align="left" class="logocvr">
                                                <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">Itinerary Details</h4>
                                            </td>

                                        </tr>

                                    </table>

                                    <table width="100%" border="0" cellpadding="" cellspacing="0"
                                        style="border-collapse: collapse; font-size: 13px; color: #374c5d;">

                                        <?php

                                 foreach ($tours_itinerary_dw as $key => $itinary) {

                                   $accommodation = $itinary['accomodation'];

                                   $accommodation = json_decode($accommodation);

                                   ?>

                                        <tr>

                                            <td width="10%" align="left"
                                                style="padding:10px;color: #374c5d;border:solid 1px #ccc; font-weight:bold; vertical-align: top;">
                                                Day <?php echo $key+1; ?></td>

                                            <td width="20%"
                                                style="border:solid 1px #ccc;padding:10px; vertical-align: top;">

                                                <strong>Visiting Place:</strong>

                                                <p style="margin-top:0; margin-bottom:5px; vertical-align: top;">
                                                    <?php echo  $itinary['program_title']; ?></p>

                                            </td>

                                            <!-- <td width="20%" style="border:solid 1px #fff"><strong>Night Stay:</strong>

                                    <p style="margin-top:0; margin-bottom:5px;">

                                      <?php echo  $itinary['program_title']; ?>                      

                                    </p>

                                    </td> -->

                                            <td width="50%" style="border:solid 1px #ccc;padding:10px;">

                                                <strong>Description:</strong>

                                                <p style="margin:0;"><?php echo  $itinary['program_des'];   ?></p>

                                                <!-- <p style="margin:0;">Overnight at hotel <?=$itinary['hotel_name']?></p>-->

                                                <p>

                                                    <?php 

                                          if($itinary['rating'])

                                          {

                                            ?>

                                                    <img src="http://www.ziphop.com/extras/custom/keWD7SNXhVwQmNRymfGN/images/star_rating_<?=$itinary['rating']?>.png"
                                                        alt="">

                                                    <?php

                                          }

                                          ?>

                                                </p>

                                                <?php 

                                       $meals = json_decode($itinary['accomodation'],true);

                                       $meals = implode(' | ',$meals);

                                       ?>

                                                <!--  <strong>Meal Plan: <?=$meals; ?></strong>-->

                                            </td>

                                        </tr>

                                        <?php

                                 }

                                 ?>

                                    </table>

                                    <tr>

                                        <td>&nbsp;</td>

                                    </tr>



                                    <table width="100%" border="0" cellpadding="6" cellspacing="0"
                                        style="border-collapse: collapse;">

                                        <tr style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">
            <td  align="left" class="logocvr">
              <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">Passenger Details</h4>
            </td>
          </tr>

                                    </table>

                                    <table style="color: #374c5d; font-size: 13px; width: 100%;">

                                        <tr>

                                            <!-- <tr><td>&nbsp;</td></tr> -->



                                            <?php //debug(($attributes));

                                       $adult_count=0;$child_count=0;

                                       foreach($attributes->passenger_type as $pt)

                                       {

                                           //echo $pt;

                                           if($pt==1){$adult_count++;}

                                           elseif($pt==2){$child_count++;}

                                           else{}

                                       }

                                       $last_name=$attributes->last_name;

                                       $first_name=$attributes->first_name;

                                      

                                      

                                       //exit;

                                      

                                       ?>







                                        <tr>

                                            <td
                                                style="width:25%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                                Num of Adult</td>

                                            <td
                                                style="width:25%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                                <?php echo $adult_count; ?></td>

                                            <td
                                                style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">

                                                <?php

                                             $get_adult=0;

                                               foreach($attributes->passenger_type as $pt)

                                               {

                                                   if($pt==1)

                                                   {

                                                       $Aname=$first_name[$get_adult];

                                                       $Lname=$last_name[$get_adult];

                                                       echo $Aname.' '.$Lname; ?><br />

                                                <?php  }

                                                   

                                                   $get_adult++;

                                               } 

                                            ?>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td
                                                style="width:25%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                                Num of Child</td>

                                            <td
                                                style="width:25%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                                <?php echo $child_count; ?>

                                            <td
                                                style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">

                                                <?php

                                             $get_child=0;

                                               foreach($attributes->passenger_type as $pt)

                                               {

                                                   if($pt==2)

                                                   {

                                                       $Aname=$first_name[$get_child];

                                                       $Lname=$last_name[$get_child];

                                                       echo $Aname.' '.$Lname; ?><br />

                                                <?php  }

                                                   

                                                   $get_child++;

                                               } 

                                            ?>

                                            </td>

                            </td>

                        </tr>



                        <tr>

                            <td>&nbsp;</td>

                        </tr>



                    </table>




                    <table>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>

                    <table width="100%" border="0" cellpadding="6" cellspacing="0" style="border-collapse: collapse;">
                        <tr
                            style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">
                            <td align="left" class="logocvr">
                                <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">Supplier Details</h4>
                            </td>
                        </tr>
                    </table>

                    <table style="color: #374c5d; font-size: 13px; width: 100%;">
                        <tr>
                            <!-- <tr><td>&nbsp;</td></tr> -->

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
                            <td
                                style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                Supplier Name</td>
                            <td
                                style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                <?php echo $supplier_details[0]['first_name'].' '.$supplier_details[0]['last_name'] ?></td>


                        </tr>

                        <tr>
                            <td
                                style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                Supplier Email</td>
                            <td
                                style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                <?php echo provab_decrypt($supplier_details[0]['email']) ?></td>


                        </tr>

                        <tr>
                            <td
                                style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                Supplier Phone Number</td>
                            <td
                                style="width:50%;padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                                <?php echo $supplier_details[0]['phone'] ?></td>


                        </tr>


                    </table>


                    <tr style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">

                    <td  align="left" class="logocvr">
              <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">Purchase Summary (<?php echo $booking_details['currency_code']; ?>)</h4> </td>

            <tr>

                <td style="border: 1px solid #cccccc;">

                    <table width="100%" cellpadding="5" style="padding: 10px;font-size: 13px;">

                        <tbody>

                            <?php 

                                          $base_fare = $booking_details['fare'];

                                         // debug($booking_details);exit;

                                          //$currency_obj = new Currency(array('module_type' => 'holiday', 'from' => $booking_details['currency'], 'to' =>$CurrencyCode));

                                            $currency_obj = new Currency(array('module_type' => 'Holiday','from' => ADMIN_BASE_CURRENCY_STATIC, 'to' => get_application_currency_preference()));

  

                                         // debug($currency_obj);exit;

                                          $base_fare = $currency_obj->get_currency ( $base_fare, true, false, true);

                                          $base_fare = sprintf("%.2f", $base_fare['default_value']);

                                        //  debug($base_fare);

                                          ?>

                            <?php

                                          $convinence_amount = $currency_obj->get_currency ( $booking_details['convinence_amount'], true, false, true);

                                         

                                          

                                          $convinence_amount = $convinence_amount['default_value'];



 



                                         



                                            // debug($booking_details);exit();

                                          $base_fare=0.00;

                                          if(isset($booking_details['basic_fare']))

                                          {

                                              $base_fare=($booking_details['basic_fare']);

                                              $base_fare=str_replace(",", "", $base_fare);

                                            //  debug($base_fare);exit;

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

                                          if($attributes->convenience_fee > 0 )

                                          {

                                                  $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'holiday'));

                                                  // debug($gst_details);exit;

                                                  if($gst_details['status'] == true){

                                                      if($gst_details['data'][0]['gst'] > 0){

                                                         

                                                         

                                                         $gst_value_conv = ($attributes->convenience_fee/100) * $gst_details['data'][0]['gst'];

                                                      }

                                                  }

                                          }

                                            // $total_fare=$base_fare-$discount_value;

                                             $total_fare=$base_fare-$discount_value+$attributes->convenience_fee+$gst_value_conv-$booking_details['reward_amount'];

                                          $total_fare=number_format($total_fare, 2);

                                        // $base_fare=$base_fare-$gst_value;

                                          $base_fare=number_format($base_fare, 2);

                                        // $base_fare=$base_fare-$gst_value+$attributes->convenience_fee;

                                       // debug($base_fare);exit;

                                          



                      

                                           

                                          if(ceil($convinence_amount))                        

                                          {

                                          ?>

                            <tr>

                                <td style="width: 50%;">Taxes</td>

                                <td
                                    style="font-size:13px; width: 20%; text-align: right; font-weight:normal;line-height:18px;">

                                    <?php 

                                                echo sprintf("%.2f", $convinence_amount)." ".$CurrencyCode;

                                                 ?>

                                </td>

                            </tr>

                            <tr>

                                <td style="font-size:13px; width: 20%; font-weight:normal;line-height:18px;">Discount
                                </td>

                                <td
                                    style="font-size:13px; width: 20%; text-align: right; font-weight:normal;line-height:18px;">

                                    <?php

                                                echo $discount = sprintf("%.2f", $booking_details['discount'])." ".$CurrencyCode;

                                                ?>

                                </td>

                            </tr>

                            <?php

                                          }

                                          ?>



                            <tr>

                                <td style="font-size:13px; width: 50%; font-weight:normal;line-height:18px;">
                                    <strong>Total Price </strong>
                                </td>

                                <td style="font-size:13px; width:50%;  font-weight:normal;line-height:18px;">
                                    <strong><?php echo $base_fare;

                                              // echo sprintf("%.2f", ceil($booking_details['payment_mode'])); ?></strong>
                                </td>

                            </tr>



                            <!-- <tr>

                                          <td style="font-size:13px; width: 50%; font-weight:normal;line-height:18px;"><strong>GST </strong></td>

                                          <td style="font-size:13px; width:50%;  font-weight:normal;line-height:18px;"><strong><?php echo $gst_value;

                                               ?></strong></td>

                                       </tr>-->

                            <tr>



                                <td style="font-size:13px; width: 50%; font-weight:normal;line-height:18px;">
                                    <strong>Convenience Fee </strong>
                                </td>

                                <td style="font-size:13px; width:50%;  font-weight:normal;line-height:18px;"><strong><?php echo number_format( $attributes->convenience_fee,2);

                                               ?></strong></td>

                            </tr>

                            <?php if($gst_value_conv >0){?>

                            <tr>



                                <td style="font-size:13px; width: 50%; font-weight:normal;line-height:18px;"><strong>GST
                                    </strong></td>

                                <td style="font-size:13px; width:50%;  font-weight:normal;line-height:18px;"><strong><?php echo number_format( $gst_value_conv,2);

                                               ?></strong></td>

                            </tr>

                            <?php }?>

                            <tr>

                                <td style="font-size:13px; width: 50%; font-weight:normal;line-height:18px;">
                                    <strong>Discount </strong>
                                </td>

                                <td style="font-size:13px; width:50%;  font-weight:normal;line-height:18px;"><strong><?php echo $rs=number_format($discount_value+ $booking_details['reward_amount'],2);

                                               ?></strong></td>

                            </tr>









                            <tr>

                                <td style="font-size:13px; width: 50%; font-weight:normal;line-height:18px;">
                                    <strong>Grand Total </strong>
                                </td>

                                <td style="font-size:13px; width:50%;  font-weight:normal;line-height:18px;">
                                    <strong><?php echo $total_fare;

                                              // echo sprintf("%.2f", ceil($booking_details['payment_mode'])); ?></strong>
                                </td>

                            </tr>

                    </table>

                </td>

                <?php if($rate_comment['status']){

                                 ?>

            <tr>

                <td>&nbsp;</td>

            </tr>

            <!-- <tr class="">

                              <td colspan="6" style="padding: 8px 14px; text-align: justify; font-size:13px; color: #374c5d;"><strong>Hotel Policy</strong><br><?php echo $rate_comment['comment'][0]['rateComments'][0]['description']; ?></td>

                              </tr> -->

            <?php }else{ ?>

            <!-- <tr><td>&nbsp;</td></tr>

                              <tr class="hide">

                                  <td colspan="6" style="padding: 8px 14px; color: #374c5d; text-align: justify; font-size:13px"><strong>Hotel Policy</strong><br>Not Available</td>

                              </tr> -->

            <?php 

                              }

                              ?>

            <?php if(isset($attributes->special_req) && !empty($attributes->special_req)){

                              ?>

            <!-- <tr class="hide">

                              <td colspan="6" style="padding: 8px 14px; line-height: 20px; text-align: justify; font-size:13px; color: #374c5d;"><strong>Special Requests</strong><br>

                              <ul>

                              <?php 

                                 foreach ($attributes->special_req as $special_req_key => $special_req_value) {

                                  echo "<li>".$special_req_value."</li>";

                                 }

                                  ?>

                               </ul>      

                               </td>

                              </tr> -->

            <?php }else{ ?>

            <!-- <tr class="hide">

                              <td colspan="6" style="padding: 8px 14px; text-align: justify; font-size:13px; color: #374c5d;"><strong>Special Requests</strong><br> Not Available</td>

                              </tr> -->

            <?php 

                              }

                              ?>

            <?php if(isset($attributes->users_comments) && !empty($attributes->users_comments)){

                              ?>

            <!-- <tr class="hide">

                              <td colspan="6" style="padding: 8px 14px; text-align: justify; font-size:13px; color: #374c5d;"><strong>Customer Remarks</strong>

                              <br><?php echo $attributes->users_comments; ?></td>

                              </tr> -->

            <?php }else{ ?>

            <!-- <tr class="hide">

                              <td colspan="6" style="padding: 8px 14px; text-align: justify; font-size:13px; color: #374c5d;"><strong>Customer Remarks</strong>

                              <br>Not Available</td>

                              </tr> -->

            <?php 

                              }

                              ?>

            <tr>

                <td width="50%" style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; font-weight: bold;">
                    <strong>Supplier

                        Information</strong><br>Payable through <?php echo $attributes->supplier; ?> -
                    <?php echo $attributes->supplier_vat; ?>, acting as agent for the service operating company, details
                    of which can be provided upon request.

                </td>

            </tr>

            </tr>

            <tr>

                <td>&nbsp;</td>

            </tr>

            <tr style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">

                <!-- <tr>

                                 <td colspan="6" style="padding: 8px 14px; text-align: justify; color: #374c5d; font-size:13px">

                                 <strong>Cancellation

                                         Policies</strong>

                                     <br/>

                                     <ul>

                                         <?php

                                    foreach ($cancel_policies as $key => $value) {

                                        ?>

                                             <li><?php echo $value . '(Based on Destination Time)<br/>'; ?></li>

                                         <?php }

                                    ?>

                                     </ul>

                                 

                                 </td>

                                 </tr> -->

                <!-- <tr>

                                 <td style="padding: 8px; text-align: justify; font-size:13px"><strong>Amendment

                                         Policies</strong> - We're here to help! If you need assistance

                                     with your reservation, please visit our Help Center. For urgent

                                     situations,: such as check-in troubles or arriving to something

                                     unexpected</td>

                                 </tr> -->

                                 <td  align="left" class="logocvr">
              <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">Terms & Conditions</h4>
            </td>

            </tr>

            <tr>

                <?php if($tours_details['terms']){ ?>

                <td width="100%" style="border: 1px solid #cccccc;">

                    <table width="100%" cellpadding="5" style="padding: 10px 20px;font-size: 13px;">

                        <tbody>

                            <tr>

                                <td>

                                    <?=html_entity_decode($tours_details['terms'])?>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </td>

                <?php }else{ ?>

                <td width="100%" style="border: 1px solid #cccccc;">

                    <table width="100%" cellpadding="5" style="padding: 10px 20px;font-size: 13px;">

                        <tbody>

                            <tr>

                                <td>Not Available</td>

                            </tr>

                        </tbody>

                    </table>

                </td>

                <?php } ?>

            </tr>

            <tr>

                <td>&nbsp;</td>

            </tr>

            <?php if($tours_details['inclusions']){ ?>

               <tr style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">
            <td  align="left" class="logocvr">
              <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">
                    Tour Inclusions</h4>

                </td>

            </tr>

            <tr>

                <td width="50%" style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; ">
                    <?php //echo $attributes->terms_n_condition; ?>

                    <?=html_entity_decode($tours_details['inclusions'])?>

                </td>

            </tr>

            <tr>

                <td>&nbsp;</td>

            </tr>

            <?php } ?>

            <?php if($tours_details['exclusions']){ ?>

               <tr style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">
            <td  align="left" class="logocvr">
              <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">
                    Tour Exclusions</h4>

                </td>

            </tr>

            <tr>

                <td width="50%" style="padding: 10px;border: 1px solid #cccccc; font-size: 13px; ">
                    <?php //echo $attributes->terms_n_condition; ?>

                    <?=html_entity_decode($tours_details['exclusions'])?>

                </td>

            </tr>

            <?php } ?>

            </tr>

            <tr>

                <td>&nbsp;</td>

            </tr>
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

            <tr style="background-color: #009edb;border: 1px solid #009edb;color: #fff;font-size: 14px;padding: 5px;">
            <td  align="left" class="logocvr">
              <h4 style=" color: #fff;     font-size: 16px; margin: 0px; line-height: 20px; text-align: left; font-weight: bold;
                                      ">

                    Contact Information</h4>

                </td>

            </tr>

            <tr>

                <td width="100%" style="border: 1px solid #cccccc;">

                    <table width="100%" cellpadding="5" style="padding: 10px 20px;font-size: 13px;">

                        <tbody>

                            <tr>

                                <td> For any questions or inquiries please email us at <?=$data['voucher_email']?>. Address:<?=$data['address']?> or contact our
                                    24/7 Help Desk at <span style="color:#39b274; "><?=$data['voucher_phone']?>,</span> our
                                    dedicated and well experienced staff will assist you all the way.</td>

                            </tr>

                        </tbody>

                    </table>

                </td>

            </tr>

            <tr style="display: none;">

                <td style="text-align:left; color: #374c5d; font-size: 13px;border-collapse: collapse;padding:0px;"><br>

                    We at Troogles are here to make your travel worry free. So zip freely, hop often and remember to
                    always enjoy the journey!

                </td>

            </tr>

            </td>

            </tr>

            <tr style="display: none;">

                <td style="padding: 0 0px 0 0px;-webkit-text-size-adjust: none;border-collapse: collapse">

                </td>

            </tr>
                 
<tr>
    <td>
    <img src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v3/images/order1.jpg"
                                        alt="" align="left" style="width:120px;">
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

            <!--                   <tr class="hide">

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

    <tr style="display:none;">

        <td style="border: none;">

            <div class="foot_bottom">

                <ul>

                    <!--    <li class="list-unstyled">                    <p></p>                </li> -->

                    <!-- <li class="list-unstyled"><a class="col_fb" href="https://www.facebook.com/tripmiaa"><i class="fab fa-facebook-f"></i></a></li>

                              <li class="list-unstyled"><a class="col_twt" href="https://twitter.com/tripmiaa"><i class="fab fa-twitter"></i></a></li>

                              <li class="list-unstyled"><a class="col_istg" href="https://www.instagram.com/tripmiaa"> <i class="fab fa-instagram"></i></a></li>

                              <li class="list-unstyled"><a class="col_lin" href="https://www.linkedin.com/tripmiaa"><i class="fab fa-linkedin-in"></i></a></li>

                              <li class="list-unstyled"><a class="col_lin" href="https://www.youtube.com/tripmiaa"><i class="fab fa-youtube"></i></a></li> -->

                </ul>

            </div>

        </td>

    </tr>


    </table>

    </td>

    </tr>

    </tbody>

    </table>

    <!--   <table id="printOption"

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

</div>

<div style="text-align: center;">

    <a href="<?php echo base_url();?>" class="btn btn-primary btfl">Back</a>

    <?php if($booking_details['status']=="BOOKING_CONFIRMED"){?>

    <button type="button" onClick="printDiv('tickect_hotel')" class="btn btn-primary btfl">Print</button>

    <?php }?>

</div>

<script type="text/javascript">
function printDiv(divName) {

    var printContents = document.getElementById(divName).innerHTML;

    var originalContents = document.body.innerHTML;



    document.body.innerHTML = printContents;

    document.title = "Holiday Ticket-Travel Free Travels";

    window.print();



    document.body.innerHTML = originalContents;

}
</script>

<style>
[type="checkbox"]:not (:checked),
[type="checkbox"]:checked {

    left: 135px;

    position: absolute;

}
</style>

<script>
$(document).ready(function() {

    $('.btn-popup').on('click', function(e) {

        e.preventDefault();

    });

    $('#btn-togglr-fare').on('change', function() {

        var $check = $('#inc_fare');

        if ($(this).prop('checked')) {

            //$(this).addClass('btn-checked');

            $('.fare-details').hide();

            $check.val(0);

        } else {

            //$(this).removeClass('btn-checked');

            $('.fare-details').show();

            $check.val(1);

        }

    });

    $('#btn-togglr-address').on('change', function() {

        var $check = $('#inc_sddress');

        if ($(this).prop('checked')) {

            $('.address-details').hide();

            $check.val(0);

        } else {

            $('.address-details').show();

            $check.val(1);

        }



    });

});
</script>