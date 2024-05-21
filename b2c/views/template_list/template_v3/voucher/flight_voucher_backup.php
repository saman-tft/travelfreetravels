<?php
$booking_details = $data ['booking_details'] [0];

$conversion_rate = $booking_details['currency_conversion_rate'];
$segment_discount = $booking_details['segment_discount'];

$itinerary_details = $booking_details ['booking_itinerary_details'];

// debug($itinerary_details);exit;

$attributes = $booking_details ['attributes'];

$customer_details = $booking_details ['booking_transaction_details'] [0] ['booking_customer_details'];

$domain_details = $booking_details;

$lead_pax_details = $customer_details;

$booking_transaction_details = $booking_details ['booking_transaction_details'];
$trans_attributes = json_decode($booking_transaction_details[0]['attributes'], true);

$segment_discount = $trans_attributes['segment_discount'];

if((isset($trans_attributes['conv_price_details'])) && !empty($trans_attributes['conv_price_details'])){
    $conv_price_details = $trans_attributes['conv_price_details'];

    if($conv_price_details['markup_type'] == 'Pecentage'){
        $markup_value = ($conv_price_details['BaseFare'] * $conv_price_details['original_markup']) / 100;
    }else{
        $markup_value = $conv_price_details['original_markup'] * $conversion_rate;
    }
    
    $booking_customer_det = $booking_details['booking_transaction_details'][0]['booking_customer_details'];
    $tot_pac = count($booking_customer_det);
    if($trans_attributes['conv_price_details']['convinence_fees_type'] == 'plus'){
        $convenience_fees = $trans_attributes['conv_price_details']['convinence_fees'];
    }else{
        $tot = ($trans_attributes['conv_price_details']['BaseFare'] + $trans_attributes['conv_price_details']['Tax'] + $markup_value);
        $convenience_fees = ($tot * $trans_attributes['conv_price_details']['convinence_fees']) / 100;
        $convenience_fees = round($convenience_fees);
    }
    if($booking_details['trip_type'] == 'oneway'){
        $multiplier_conve = 1;
    }else{
        $multiplier_conve = 2;
    }
    $convenience_fees = $convenience_fees * $multiplier_conve;
    $convenience_fees = ($convenience_fees * $tot_pac);
}else{
    $markup_value = round($booking_details['admin_markup']);
    $convenience_fees = $booking_details['convinence_amount'];
}


$adult_count = 0;

$infant_count = 0;

foreach ($customer_details as $k => $v) {

    if (strtolower($v ['passenger_type']) == 'infant') {

        $infant_count ++;

    } else {

        $adult_count ++;

    }

}



$Onward = '';

$return = '';

if (count($booking_transaction_details) == 2) {

    $Onward = 'Onward ';

    $Return = 'Return ';

}

// generate onword and return

if ($booking_details ['is_domestic'] == true && count($booking_transaction_details) == 2) {

    $onward_segment_details = array();

    $return_segment_details = array();

    $segment_indicator_arr = array();

    $segment_indicator_sort = array();

    foreach ($itinerary_details as $key => $key_sort_data) {

        $segment_indicator_sort [$key] = $key_sort_data ['origin'];

    }

    array_multisort($segment_indicator_sort, SORT_ASC, $itinerary_details);

    foreach ($itinerary_details as $k => $sub_details) {

        $segment_indicator_arr [] = $sub_details ['segment_indicator'];

        $count_value = array_count_values($segment_indicator_arr);

        if ($count_value [1] == 1) {

            $onward_segment_details [] = $sub_details;

        } else {

            $return_segment_details [] = $sub_details;

        }

    }

}

// debug($onward_segment_details);exit;

/*if (isset($onward_segment_details[0]['airline_pnr']) && !empty($itinerary_details[0]['airline_pnr'])) {

    $airline_pnr = $itinerary_details[0]['airline_pnr'];

    $gds_pnr = $booking_transaction_details[0]['pnr'];

} else if (!empty($booking_transaction_details[0]['pnr'])) {

    $airline_pnr = $booking_transaction_details[0]['pnr'];

    $gds_pnr = $booking_transaction_details[0]['pnr'];

} else {

    $airline_pnr = $booking_transaction_details[0]['book_id'];

    $gds_pnr = $booking_transaction_details[0]['book_id'];

}*/
if (isset($onward_segment_details[0]['airline_pnr']) && !empty($itinerary_details[0]['airline_pnr'])) {
    $airline_pnr = $itinerary_details[0]['airline_pnr'];
    $gds_pnr = $booking_transaction_details[0]['pnr'];
} else if (!empty($booking_transaction_details[0]['pnr'])) {


    if(isset($itinerary_details[0]['airline_pnr']))
    {
        $airline_pnr = $itinerary_details[0]['airline_pnr'];
    } else {
	   $airline_pnr = $booking_transaction_details[0]['pnr'];
    }
    $gds_pnr = $booking_transaction_details[0]['pnr'];
} else {
    $airline_pnr = $booking_transaction_details[0]['book_id'];
    $gds_pnr = $booking_transaction_details[0]['book_id'];
}

if (isset($return_segment_details)) {

    // debug($booking_transaction_details);exit;

    if (isset($return_segment_details[0]['airline_pnr']) && !empty($return_segment_details[0]['airline_pnr'])) {

        $return_airline_pnr = $return_segment_details[0]['airline_pnr'];

        $return_gds_pnr = $booking_transaction_details[1]['pnr'];

    } elseif (!empty($booking_transaction_details[1]['pnr'])) {

        $return_airline_pnr = $booking_transaction_details[1]['pnr'];

        $return_gds_pnr = $booking_transaction_details[1]['pnr'];

    } else {

        $return_airline_pnr = $booking_transaction_details[1]['book_id'];

        $return_gds_pnr = $booking_transaction_details[1]['book_id'];

    }

    $retur_fare_details = json_decode($booking_transaction_details[1]['attributes'], True);

}



$fare_details = json_decode($booking_transaction_details[0]['attributes'], True);



$currency_conversion_rate=$booking_details['currency_conversion_rate'];

if((isset($trans_attributes['conv_price_details'])) && !empty($trans_attributes['conv_price_details'])){
    //debug($trans_attributes['conv_price_details']);
    $BaseFare = $trans_attributes['conv_price_details']['BaseFare'];
    $Tax = $trans_attributes['conv_price_details']['Tax'];
    $grand_total = $trans_attributes['conv_price_details']['PublishedFare'] + $markup_value + $convenience_fees;
}else{
    $BaseFare = ($currency_conversion_rate*$fare_details['Fare']['BaseFare'] + @$retur_fare_details['Fare']['BaseFare']);    
    $Tax = ($currency_conversion_rate*$fare_details['Fare']['Tax'] + @$retur_fare_details['Fare']['Tax']);
    $grand_total = $booking_details['grand_total'];
}




$GST = $booking_transaction_details[0]['gst'] + @$booking_transaction_details[1]['gst'];

// $GST = $booking_transaction_details[0]['gst'];

// debug($booking_transaction_details);exit;

$booking_transaction_details_value = $booking_transaction_details [0];

$baggage_price = 0;

$meal_price = 0;

$seat_price = 0;

// debug($booking_transaction_details_value);exit;

if (isset($booking_transaction_details_value['extra_service_details']['baggage_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['baggage_details']) == true) {

    $baggage_details = $booking_transaction_details_value['extra_service_details']['baggage_details'];

    foreach ($baggage_details['details'] as $bag_k => $bag_v) {

        foreach ($bag_v as $bd_k => $bd_v) {

            $baggage_price += $bd_v['price'];

        }

    }

}

if (isset($booking_transaction_details_value['extra_service_details']['meal_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['meal_details']) == true) {

    // debug($booking_transaction_details_value);exit;

    $meal_details = $booking_transaction_details_value['extra_service_details']['meal_details'];

    foreach ($meal_details['details'] as $meal_k => $meal_v) {

        foreach ($meal_v as $md_k => $md_v) {

            $meal_price += $md_v['price'];

        }

    }

}

if (isset($booking_transaction_details_value['extra_service_details']['seat_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['seat_details']) == true) {

    $seat_details = $booking_transaction_details_value['extra_service_details']['seat_details'];

    foreach ($seat_details['details'] as $seat_k => $seat_v) {

        foreach ($seat_v as $sd_k => $sd_v) {

            // debug($seat_v);exit;

            $seat_price += $sd_v['price'];

        }

    }

}

if ($booking_details ['is_domestic'] == true && count($booking_transaction_details) == 2) {

    $booking_transaction_details_value = $booking_transaction_details [1];

    if (isset($booking_transaction_details_value['extra_service_details']['baggage_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['baggage_details']) == true) {

        $baggage_details = $booking_transaction_details_value['extra_service_details']['baggage_details'];

        foreach ($baggage_details['details'] as $bag_k => $bag_v) {

            foreach ($bag_v as $bd_k => $bd_v) {

                $baggage_price += $bd_v['price'];

            }

        }

    }

    if (isset($booking_transaction_details_value['extra_service_details']['meal_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['meal_details']) == true) {

        // debug($booking_transaction_details_value);exit;

        $meal_details = $booking_transaction_details_value['extra_service_details']['meal_details'];

        foreach ($meal_details['details'] as $meal_k => $meal_v) {

            foreach ($meal_v as $md_k => $md_v) {

                $meal_price += $md_v['price'];

            }

        }

    }

    if (isset($booking_transaction_details_value['extra_service_details']['seat_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['seat_details']) == true) {

        $seat_details = $booking_transaction_details_value['extra_service_details']['seat_details'];

        foreach ($seat_details['details'] as $seat_k => $seat_v) {

            foreach ($seat_v as $sd_k => $sd_v) {

                // debug($seat_v);exit;

                $seat_price += $sd_v['price'];

            }

        }

    }

}



$airline_contact_no = $this->custom_db->single_table_records('airline_contact_numbers', '*', array('airline_code' => $itinerary_details[0]['airline_code']));

// debug($airline_contact_no);exit;

if (isset($airline_contact_no['data'][0])) {

    $airline_number = '<span><img alt="" style="vertical-align:middle" src=' .base_url(). SYSTEM_IMAGE_DIR . 'phone.png /><span style="font-size:16px;color:#2196f3;vertical-align:middle;font-weight: 600;"> &nbsp;' . $airline_contact_no['data'][0]['phone_number'] . '</span></span> ';

} else {

    $airline_number = '';

}

if ($booking_transaction_details[0] ['status'] == 'BOOKING_CONFIRMED') {

    $border = 'border-top:2px solid #808080;';

} else if ($booking_transaction_details[0] ['status'] == 'BOOKING_HOLD') {

    $border = '';

}

$trip_type = $booking_details['trip_type'];

// echo $meal_price;exit;

// debug($fare_details);exit;

?>

<style type="text/css" media="print">

@page {

    size: auto;   /* auto is the initial value */

    margin: 0;  /* this affects the margin in the printer settings */

}



</style>

<style type="text/css">

    td, th {padding: 5px;}

    @media print { 

        header, footer, #show_log { display: none; }

        .pag_brk { page-break-before: always; }

    }

</style>

<div id="printpage" style="width:100%;" >

<div style="background:#fff; width:100%; position:relative;padding: 20px 0px;">



    <table class="fltvoch" cellpadding="0" border-collapse cellspacing="0" width="100%" style="font-size:12px; font-family: 'Open Sans', sans-serif; width:900px; margin:0px auto;background-color:#fff; padding:45px;border-collapse:separate;color: #000;box-shadow: 0 0 5px 0 rgba(0,0,0,.11);border-radius:8px;">

        

        <tbody width="100%">

            <tr>

                              <td colspan="4" style="font-size:18px; line-height:30px; width:100%;font-weight:600; text-align:center">Flight Voucher</td>

                           </tr>

            <tr>

                <td colspan="2" style="padding-bottom:10px"><img style="width:200px;" src="<?=$GLOBALS['CI']->template->domain_images($data['logo'])?>" alt="" /></td>

                <td colspan="2" style="padding-bottom:10px" align="right"><span>Booking ID : <?= @$booking_details['app_reference'] ?></span><br><span>Booked on : <?= app_friendly_absolute_date(@$booking_details['booked_date']) ?></span></td>

            </tr>

            <tr>

                <td align="right" colspan="4" style="line-height:26px; border-top:1px solid #009edb; border-bottom:1px solid #009edb;"><span style="font-size:12px;">Status: </span><strong class="<?php echo booking_status_label($booking_transaction_details[0]['status']) ?>" style=" font-size:14px;">

<?php

switch (@$booking_transaction_details[0] ['status']) {

    case 'BOOKING_CONFIRMED' :

        echo 'CONFIRMED';

        break;

    case 'BOOKING_CANCELLED' :

        echo 'CANCELLED';

        break;

    case 'BOOKING_FAILED' :

        echo 'FAILED';

        break;

    case 'BOOKING_INPROGRESS' :

        echo 'INPROGRESS';

        break;

    case 'BOOKING_INCOMPLETE' :

        echo 'INCOMPLETE';

        break;

    case 'BOOKING_HOLD' :

        echo 'HOLD';

        break;

    case 'BOOKING_PENDING' :

        echo 'PENDING';

        break;

    case 'BOOKING_ERROR' :

        echo 'ERROR';

        break;

}

?></strong></td>

            </tr>

                        <?php if ($booking_details ['is_domestic'] == true && count($booking_transaction_details) == 2) { ?>

                <tr>

                    <td colspan="4" style="font-size: 18px;font-weight: 600;text-align: center;padding: 10px 0 0;">E-Ticket <?php echo $Onward; ?></td>

                </tr>

                        <?php } ?>

            <tr>

                <td class="tdimg" style="padding:10px 0"><img style="width:60px;" src="<?= base_url().SYSTEM_IMAGE_DIR . 'airline_logo/'. $itinerary_details[0]['airline_code'] . '.gif' ?>" alt="" /></td>

                <td class="tdimg1" style="padding:10px 0; line-height:25px;"><span style="display: block;border-right: 1px solid #999;"><span style="font-size:14px;"><?= @$itinerary_details[0]['airline_name'] ?></span><br><?php echo $airline_number ?></span></td>

                <td class="tdimg2" class="agtd" style="padding:10px 0; padding-left: 10%; line-height:25px;"><span style="font-size:14px;display: block;">Travel Free Travels customer service</span>



                    <span><img style="vertical-align:middle" src="<?= base_url().SYSTEM_IMAGE_DIR . 'envelope.png' ?>" alt=""/>

                        <span style="font-size:16px;color:#2196f3;vertical-align:middle;font-weight: 600;"> &nbsp;<?= $this->entity_domain_mail ?></span></span>



                    </td>

<?php if ((!empty($gds_pnr)) || (!empty($airline_pnr))) { ?>

                    <td class="tdimg3" style="padding:10px 0;text-align: center;">

                        <span style="font-size:14px; border:2px solid #808080; display:block">

    <?php if ($booking_transaction_details[0] ['status'] == 'BOOKING_CONFIRMED') { ?>

                                <span style="color:#1FB53A;padding:4px; display:block">AIRLINE PNR</span>

                                <span style="font-size:16px;line-height:12px;padding-bottom: 5px;display:block;font-weight: 600;"><?= @$airline_pnr ?></span>

                    <?php } ?>

                    <?php if (($booking_transaction_details[0] ['status'] == 'BOOKING_CONFIRMED' || $booking_transaction_details[1] ['status'] == 'BOOKING_HOLD') && !empty($gds_pnr)) { ?>

                                <span style="<?php echo $border; ?>display:block; padding:0px;">GDS PNR:  <?= @$gds_pnr ?></span>

                            <?php } ?>

                        </span></td>

<?php } ?>

            </tr>

           <tr> 

            <tr>

                <td colspan="4" style="border:1px solid #009edb; padding:0;">

                    <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">

                        <tbody>

                            <tr>

                                <td colspan="2" style="background-color:#009edb; color:#fff"><img style="vertical-align:middle" src="<?= base_url().SYSTEM_IMAGE_DIR . 'flight.png' ?>" alt=""> &nbsp;<span style="vertical-align:middle;font-size:13px">Onward Flight Details</span></td>

                                <td align="right" colspan="2" style="background-color:#009edb; color:#fff"><span style="font-size:10px">*Please verify flight times with the airlines prior to departure</span></td>

                            </tr>



<?php

if (isset($booking_transaction_details) && $booking_transaction_details != "") {

    if ($booking_details ['is_domestic'] == true && count($booking_transaction_details) == 2) {

        $itinerary_details = array();

        $itinerary_details = $onward_segment_details;

    }

    // debug($itinerary_details);exit;

    $checkin_baggage = 0;

    $cabin_baggage = 0;

    $seg_count = count($itinerary_details);

    if ($seg_count == 1) {

        $non_stop = 'Non Stop';

    } else {

        $non_stop = '';

    }

    $seg_in_array = array();

    $seg_array = array();

    $seg_counts = 0;

    // debug($itinerary_details);exit;

    foreach ($itinerary_details as $segment_details_k => $segment_details_v) {

        // echo $trip_type;

        $seg_array [] = $segment_details_v['segment_indicator'];

        // debug($seg_array);

        if (in_array($segment_details_v['segment_indicator'], $seg_in_array) && !empty($seg_in_array) && ($seg_counts == 0) && ($trip_type != 'multicity')) {

            // echo $seg_count;

            $seg_counts = $seg_counts + 1;



            // debug($seg_in_array);

            ?>

                                        <tr>

                                            <td colspan="2" style="background-color:#1FB53A; color:#fff"><img style="vertical-align:middle" src="<?= base_url().SYSTEM_IMAGE_DIR . 'flight.png' ?>" alt=""> &nbsp;<span style="vertical-align:middle;font-size:13px">Return Flight Details</span></td>

                                            <td align="right" colspan="2" style="background-color:#1FB53A; color:#fff"><span style="font-size:10px">*Please verify flight times with the airlines prior to departure</span></td>

                                        </tr>



                                    <?php

                                    }



                                    $itinerary_details_attributes = json_decode($segment_details_v ['attributes'], true);

                                    $airline_terminal_origin = @$itinerary_details_attributes['departure_terminal'];

                                    $airline_terminal_destination = @$itinerary_details_attributes['arrival_terminal'];

                                    $origin_terminal = '';

                                    $destination_terminal = '';

                                    // debug($itinerary_details_attributes);exit;

                                    if ($airline_terminal_origin != '') {

                                        $origin_terminal = 'Terminal ' . $airline_terminal_origin;

                                    }

                                    if ($airline_terminal_destination != '') {

                                        $destination_terminal = 'Terminal ' . $airline_terminal_destination;

                                    }





# Checking CheckIN Baggage



                                    if (is_int($segment_details_v['checkin_baggage'])) {

                                        $BaggageSegment_checkin .= 'Checkin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . (int) $segment_details_v['checkin_baggage'] . ' Kg & Child :'.(int) $segment_details_v['checkin_baggage'] .' Kg)<br />';

                                        $TotalBaggageCheckIN = (int) $segment_details_v['checkin_baggage'];

                                    } else {

                                        $BaggageSegment_checkin .= 'Checkin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . $segment_details_v['checkin_baggage'] . ' & Child :'.$segment_details_v['checkin_baggage'] .')<br />';

                                        $TotalBaggageCheckIN = $segment_details_v['checkin_baggage'];

                                    }



# Checking Cabin Baggage



                                    if (is_int($segment_details_v['cabin_baggage'])) {

                                        $BaggageSegment_cabin .= 'Cabin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . (int) $segment_details_v['cabin_baggage'] . ' Kg & Child :'.(int) $segment_details_v['cabin_baggage'].')<br />';

                                        $TotalBaggageCabin = (int) $segment_details_v['cabin_baggage'];

                                    } else {

                                        $BaggageSegment_cabin .= 'Cabin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . $segment_details_v['cabin_baggage'] . ' & Child : '.$segment_details_v['cabin_baggage'].')<br />';

                                        $TotalBaggageCabin = $segment_details_v['cabin_baggage'];

                                    }







                                    if ($seg_count != 1 && $trip_type != 'multicity') {



                                        if (count(array_unique($seg_array)) == 1 && end($seg_array) == 1 && $seg_count == 2 && $trip_type != 'oneway') {



                                            $non_stop = 'Non Stop';

                                        } else {

                                            $non_stop = $segment_details_v['segment_indicator'] . ' Stop';

                                        }

                                    } else if ($seg_count != 1) {

                                        $non_stop = ($segment_details_k + 1) . ' Stop';

                                    }

                                    if ($trip_type == 'multicity') {

                                        $fight_count = ($segment_details_k + 1);

                                    } else {

                                        $fight_count = $segment_details_v['segment_indicator'];

                                    }

                                    $seg_in_array[] = $segment_details_v['segment_indicator'];

                                    if (valid_array($segment_details_v) == true) {

                                        ?>

                                        <tr>

                                            <td style="background-color:#d9d9d9; color:#555555"><span style="color:#006f8e">Flight <?php echo $fight_count; ?></span></td>

                                            <td style="background-color:#d9d9d9; color:#555555"><img style="vertical-align:middle" src="<?= base_url().SYSTEM_IMAGE_DIR . 'flight_up.png' ?>" alt="">&nbsp;<span style="vertical-align:middle">Departing</span></td>

                                            <td style="background-color:#d9d9d9; color:#555555"><img style="vertical-align:middle" src="<?= base_url().SYSTEM_IMAGE_DIR . 'flight_down.png' ?>" alt="">&nbsp;<span style="vertical-align:middle">Arriving</span></td>

                                            <td style="background-color:#d9d9d9; color:#555555">&nbsp;</td>

                                        </tr>

                                        <tr>

                                            <td><span><?= @$segment_details_v['airline_name'] ?><br><?php echo $segment_details_v['airline_code'] . ' - ' . $segment_details_v['flight_number']; ?><br>Cabin: <?php echo $booking_details['cabin_class']; ?></span></td>

                                            <td><span><strong><?= @$segment_details_v['from_airport_name'] ?>(<?= @$segment_details_v['from_airport_code'] ?>)</strong><br><?php echo date("D, d M Y", strtotime($segment_details_v['departure_datetime'])) . ", " . date("h:i A", strtotime($segment_details_v['departure_datetime'])); ?><br><?php echo $origin_terminal; ?></span></td>

                                            <td><span><strong><?= @$segment_details_v['to_airport_name'] ?>(<?= @$segment_details_v['to_airport_code'] ?>)</strong><br><?php echo date("D, d M Y", strtotime($segment_details_v['arrival_datetime'])) . ", " . date("h:i A", strtotime($segment_details_v['arrival_datetime'])); ?><br><?php echo $destination_terminal; ?></span></td>

                                            <td><span style="display:block;border-left: 1px solid #999; padding-left:10%"><?php echo $non_stop; ?><br><?php echo $segment_details_v['total_duration']; ?><br><?php echo $segment_details_v['is_refundable'] ?></span></td>

                                        </tr>

            <?php

        }

    }

}

?>

                        </tbody>

                    </table>

                </td>

            </tr>

            <tr><td style="line-height:15px;padding:0;">&nbsp;</td></tr>

            <tr>

                <td colspan="4" style="border:1px solid #666666; padding:0;">

                    <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">

                        <tbody>

                            <tr>

                                <td colspan="4" style="background-color:#666666; color:#fff"><img style="vertical-align:middle" src="<?= base_url().SYSTEM_IMAGE_DIR . 'people_group.png' ?>" alt=""> &nbsp;<span style="vertical-align:middle;font-size:13px">Passenger(s) Details</span></td>

                            </tr>



                            <tr>

                                <td style="background-color:#d9d9d9; color:#555555"><span>Sr No.</span></td>

                                <td style="background-color:#d9d9d9; color:#555555"><span>Passenger(s) Name</span></td>

                                 <td style="background-color:#d9d9d9; color:#555555"><span>Passenger(s) Nationality</span></td>

                                <td style="background-color:#d9d9d9; color:#555555"><span>Type</span></td>

                                <td style="background-color:#d9d9d9; color:#555555"><span>E-ticket No</span></td>

                            </tr>

<?php

$booking_transaction_details_value = $booking_transaction_details [0];

// debug($booking_transaction_details_value);exit;

if (isset($booking_transaction_details_value ['booking_customer_details'])) {

    foreach ($booking_transaction_details_value ['booking_customer_details'] as $cus_k => $cus_v) {

        if (strtolower($cus_v['passenger_type']) == 'infant') {
$cus_v['title'] ='Mstr';
            $pass_name = $cus_v['title'] . '.' .$cus_v['first_name'] . '  ' . $cus_v['last_name'];

        } else {
if($cus_v['title'] =="")
{
    $cus_v['title'] ='Mstr';
}
            $pass_name = $cus_v['title'] . '.' . $cus_v['first_name'] . '  ' . $cus_v['last_name'];

        }

        ?>

                                    <tr>

                                        <td><span><?php echo ($cus_k + 1); ?></span></td>

                                        <td><span><strong><?php echo $pass_name; ?></strong></span></td>

                                         <td><span><?php echo ucfirst($cus_v['passenger_nationality']) ?></span></td>

                                        <td><span><?php echo ucfirst($cus_v['passenger_type']) ?></span></td>

                                        <td><span><?= @$cus_v['ticket_no']; ?></span></td>

                                    </tr>



                                    <?php

                                }

                            }

                            ?>

                        </tbody>

                    </table>

                </td>

            </tr>

            <tr><td style="line-height:15px;padding:0;">&nbsp;</td></tr>

            <tr>

                <td colspan="4" style="padding:0;">

                    <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">

                        <tbody>

                            <tr>

                                <td width="50%" style="padding:0;padding-right:14px;">

                                    <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;border:1px solid #9a9a9a;">

                                        <tbody>

                                            <tr>

                                                <td style="border-bottom:1px solid #ccc"><span style="font-size:13px">Payment Details</span></td>

                                                <td style="border-bottom:1px solid #ccc"><span style="font-size:11px">Amount ( <?php echo $booking_details['currency'] ?>

                                                        )</span></td>

                                            </tr>

                                            <tr>

                                                <td><span>Air Fare</span></td>

                                                <td><span> <?php echo number_format($BaseFare, 2) ?></span></td>

                                            </tr>

<?php



 if ($this->uri->segment(2) == 'b2c_flight_voucher'): ?>

                                                <tr>

                                                    <td><span>Taxes &amp; Fees</span></td>

                                                    <!--<td><span> <?php echo number_format(($Tax + $booking_details['admin_markup'] + $booking_details['agent_markup']) - $booking_details['admin_commission'] + $booking_details['admin_tds'], 2) ?></span></td>-->

                                                    <td><span> <?php echo number_format(($Tax + $booking_details['admin_markup'] + $booking_details['agent_markup']), 2) ?></span></td>

                                                </tr>

<?php else: ?>

                                                <tr>

                                                    <td><span>Taxes &amp; Fees</span></td>

                                                   <!--  <td><span> <?php echo number_format(($Tax + $booking_details['admin_markup'] + $booking_details['agent_markup']) - $booking_details['admin_commission'] + $booking_details['admin_tds'], 2) ?></span></td> -->

                                                    <td><span> <?php echo number_format(($Tax + $markup_value + $booking_details['agent_markup']), 2) ?></span></td>

                                                </tr>

<?php endif; ?>



<?php if ($booking_details['convinence_amount'] > 0) { ?>

                                                <tr>

                                                    <td><span>Convenience Fees</span></td>
                                                    <td><span><?php echo $convenience_fees; ?></span></td>
                                                    <!-- <td><span> <?php echo number_format(($booking_details['convinence_amount']*$currency_conversion_rate), 2) ?></span></td> -->

                                                </tr>

<?php } ?>



<!-- <?php if ($GST > 0) { ?> -->

                                                <tr>

                                                    <td><span>GST</span></td>

                                                    <td><span> <?php echo number_format(($GST), 2) ?></span></td>

                                                </tr>

<!-- <?php } ?> -->

<?php if (isset($baggage_price) && $baggage_price != 0) { ?>

                                                <tr>

                                                    <td><span>Cabin Baggage</span></td>

                                                    <td><span> <?php echo number_format($baggage_price, 2) ?></span></td>

                                                </tr>

<?php } ?>

                                            <?php if (isset($meal_price) && $meal_price != 0) { ?>

                                                <tr>

                                                    <td><span>Meals</span></td>

                                                    <td><span> <?php echo number_format($meal_price, 2) ?></span></td>

                                                </tr>

<?php } ?>

                                            <?php if (isset($seat_price) && $seat_price != 0) { ?>

                                                <tr>

                                                    <td><span>Seat</span></td>

                                                    <td><span> <?php echo number_format($seat_price, 2) ?></span></td>

                                                </tr>

<?php } if ($booking_details['discount'] != 0) { 

//debug($booking_details['discount']*$currency_conversion_rate);exit;

    ?>

                                                <tr>

                                                    <td><span>Discount (-)</span></td>

                                                    <td><span> <?php echo number_format($booking_details['discount']+$booking_details['reward_amount'], 2) ?></span></td>

                                                </tr>

<?php } if ($segment_discount != 0) { 

//debug($booking_details['discount']*$currency_conversion_rate);exit;

    ?>

                                                <tr>

                                                    <td><span>Discount (-)</span></td>

                                                    <td><span> <?php echo $segment_discount+$booking_details['reward_amount'] ?></span></td>

                                                </tr>

<?php } ?>

                                            <tr>

                                                <td style="border-top:1px solid #ccc"><span style="font-size:13px">Grand Total</span></td>

                                                <td style="border-top:1px solid #ccc"><span style="font-size:13px"> <?= round(@$grand_total-$booking_details['reward_amount']-$segment_discount) ?></span></td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </td>

                                <td width="50%" style="padding:0;padding-left:14px; vertical-align:top">

                                    <table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #9a9a9a;font-size:12px; padding:0;">

                                        <tbody>

                                            <tr>

                                                <td colspan="4" style="border-bottom:1px solid #ccc"><span style="font-size:13px">Flight Inclusions</span></td>

                                            </tr>

                                          

                                            <tr>



<?php 



if ($TotalBaggageCheckIN != '' && $TotalBaggageCheckIN != 0) { ?>

                                                    <td colspan="4"><span><?php echo $BaggageSegment_checkin; ?></span></td>



<?php } else {

    ?>

                                                    <td colspan="4"><span>Checkin Baggage : Allowed as per the Airline Policy</span></td>

<?php } ?>

                                            </tr>

                                            <tr>



<?php if ($TotalBaggageCabin != '' && $TotalBaggageCabin != 0) { ?>

                                                    <td colspan="4"><span><?php echo $BaggageSegment_cabin; ?></span></td>





                                                <?php } else {

                                                    ?>

                                                    <td colspan="4"><span>Cabin Baggage :  Allowed as per the Airline Policy</span></td>

                                                <?php } ?>

                                            </tr>











                                            <tr>

                                                <td colspan="4" style="border-top:1px solid #ccc"><span style="font-size:10px; color:#666; line-height:20px;">* Flight inclusions are subject to change with Airlines.</span></td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </td>

                            </tr>						

                            <tr><td style="line-height:15px;padding:0;">&nbsp;</td></tr>

<?php if (isset($booking_transaction_details_value['extra_service_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']) == true) { ?>

                                <tr>

                                    <td colspan="2" style="padding-top:10px; vertical-align:top; padding:0;">

                                        <table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #666666;font-size:12px; padding:0;">

                                            <tbody>

                                                <tr>

                                                    <td colspan="4" style="background-color:#666666; color:#fff;"><span style="font-size:13px">Flight Extra Information</span></td>

                                                </tr>



    <?php

    if (isset($booking_transaction_details_value['extra_service_details']['baggage_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['baggage_details']) == true) {

        $baggage_details = $booking_transaction_details_value['extra_service_details']['baggage_details'];

        foreach ($baggage_details['baggage_source_destination_label'] as $bag_lk => $bag_lv) {

            ?>

                                                        <tr><td colspan="4" style="background-color:#d9d9d9; color:#555555"><span><?php echo 'Extra Baggage Information'; ?> ( <?= $bag_lv ?> )</span></td></tr>

                                        <?php

                                        foreach ($baggage_details['details'] as $bag_k => $bag_v) {

                                            foreach ($bag_v as $bd_k => $bd_v) {

                                                // debug($bag_lv);exit;

                                                if ($bd_v['from_airport_code'] . '-' . $bd_v['to_airport_code'] == $bag_lv) {

                                                    ?>

                                                                    <tr><td colspan="4"><?= $bd_v['description'] ?> ( <?= $bag_v[0]['pax_name'] ?> )</td></tr>

                    <?php

                    }

                }

            }

        }

    }

    if (isset($booking_transaction_details_value['extra_service_details']['meal_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['meal_details']) == true) {

        $meal_details = $booking_transaction_details_value['extra_service_details']['meal_details'];

        $meal_type = end($meal_details['details']);



        $meal_type = $meal_type[0]['type'];

        if ($meal_type == 'static') {

            $meal_type_label = 'Meal Preference';

        } else {

            $meal_type_label = 'Meal Information';

        }

        foreach ($meal_details['meal_source_destination_label'] as $meal_lk => $meal_lv) {

            ?>

                                                        <tr><td colspan="4" style="background-color:#d9d9d9; color:#555555"><span><?php echo $meal_type_label; ?> ( <?= $meal_lv ?> )</span></td></tr>

                                                        <?php

                                                        foreach ($meal_details['details'] as $meal_k => $meal_v) {

                                                            foreach ($meal_v as $md_k => $md_v) {



                                                                if ($md_v['from_airport_code'] . '-' . $md_v['to_airport_code'] == $meal_lv) {

                                                                    ?>

                                                                    <tr><td colspan="4"><?= $md_v['description'] ?> ( <?= $meal_v[0]['pax_name'] ?> )</td></tr>

                                                                <?php

                                                                }

                                                            }

                                                        }

                                                    }

                                                }

                                                if (isset($booking_transaction_details_value['extra_service_details']['seat_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['seat_details']) == true) {

                                                    $seat_details = $booking_transaction_details_value['extra_service_details']['seat_details'];

                                                    $seat_type = end($seat_details['details']);

                                                    $seat_type = $seat_type[0]['type'];

                                                    if ($seat_type == 'static') {

                                                        $seat_type_label = 'Seat Preference';

                                                    } else {

                                                        $seat_type_label = 'Seat Information';

                                                    }

                                                    foreach ($seat_details['seat_source_destination_label'] as $seat_lk => $seat_lv) {

                                                        ?>

                                                        <tr><td colspan="4" style="background-color:#d9d9d9; color:#555555"><span><?php echo $seat_type_label; ?> ( <?= $seat_lv ?> )</span></td></tr>

                                                        <?php

                                                        foreach ($seat_details['details'] as $seat_k => $seat_v) {

                                                            // debug($seat_lv);exit;

                                                            foreach ($seat_v as $sd_k => $sd_v) {

                                                                if ($sd_v['from_airport_code'] . '-' . $sd_v['to_airport_code'] == $seat_lv) {

                                                                    $seat_description = trim($sd_v['description']);

                                                                    if (empty($seat_description) == true) {

                                                                        $seat_description = trim($sd_v['code']);

                                                                    }

                                                                    ?>

                                                                    <tr><td colspan="4"><?= $seat_description ?> ( <?= $seat_v[0]['pax_name'] ?> )</td></tr>

                                                                <?php }

                                                            }

                                                        }

                                                    }

                                                } ?>



                                            </tbody>

                                        </table>

                                    </td>



                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </td>

            </tr>

            <?php //debug($booking_details);exit;?>

            <tr><td style="line-height:15px;padding:0;">&nbsp;</td></tr>

            <tr><td colspan="4" style="border-bottom:1px solid #999999;padding-bottom:15px"><span style="font-size:13px; color:#555;">Customer Contact Details | E-mail : <?= $booking_details['email'] ?> | Contact No : <?= $booking_details['phone_code']." ".$booking_details['phone'] ?></span></td></tr>

            <tr><td style="line-height:15px;padding:0;">&nbsp;</td></tr>

            <tr>

                <td colspan="4"><span style="line-height:20px; font-size:13px;">Important Information</span></td></tr>

            <tr>

                <td colspan="4" style="border-bottom:1px solid #999999; line-height:20px; font-size:12px; color:#555;font-weight: 600">

                    <?php echo $data['terms_conditions']; ?>

                    <!-- <ul style="padding-bottom:10px">

                        <li>All Guests, including children and infants, must present valid identification at check-in.</li>

                        <li>Check-in begins 2 hours prior to the flight for seat assignment and closes 45 minutes prior to the scheduled departure.</li>

                        <li>Carriage and other services provided by the carrier are subject to conditions of carriage, which are hereby incorporated by reference. These conditions may be obtained from the issuing carrier.</li>

                        <li>In case of cancellations less than 6 hours before departure please cancel with the airlines directly. We are not responsible for any losses if the request is received less than 6 hours before departure.</li>

                        <li>Please contact airlines for Terminal Queries.</li>

                        <li>Free Baggage Allowance: Checked-in Baggage = 15kgs in Economy class.</li>

                        <li>Changes to the reservation will result in the above fee plus any difference in the fare between the original fare paid and the fare for the revised booking.</li>

                        

                        <li>The No Show refund should be collected within 15 days from departure date.</li>

                        <li>If the basic fare is less than cancellation charges then only statutory taxes would be refunded.</li>

                        <li>We are not be responsible for any Flight delay/Cancellation from airline's end.</li>

                        <li>Kindly contact the airline at least 24 hrs before to reconfirm your flight detail giving reference of Airline PNR Number.</li>

                        <li>We are a travel agent and all reservations made through our website are as per the terms and conditions of the concerned airlines. All modifications,cancellations and refunds of the airline tickets shall be strictly in accordance with the policy of the concerned airlines and we disclaim all liability in connection thereof.</li>

                    </ul> -->

                </td>

            </tr>

            <tr>

                <td colspan="4"><span style="line-height:20px; font-size:13px;">Note:</span></td></tr>

            <tr>

                <td colspan="4" style="border-bottom:1px solid #999999; line-height:20px; font-size:12px; color:#555;font-weight: 600">

                    <?php echo $data['note']; ?>

                </td>

            </tr>

            <!-- <tr>

                <td colspan="4" align="right" style="padding-top:10px"><?php echo $data['domainname'] ?><br><?php echo $data['address'] ?></td>

            </tr> -->
            <tr>
                                        <td colspan="4" align="right"
                                            style="padding-top:10px;font-size:13px;line-height:20px;">
                                            <img src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v3/images/order1.jpg"
                                        alt="" align="left" style="width:120px;">
                                            <?=strtoupper($data['domainname'])?><br>Phone :
                                            <?=$data['voucher_phone']?><br>Email :
                                            <?=$data['voucher_email']?><br>Address :
                                            <?=STATIC_ADDRESS;?><br><?=STATIC_COUNTRY;?></td>
                                    </tr>

        </tbody>

    </table>

</div>









<!-- Return Ticket -->

<?php

if (count($booking_transaction_details) == 2) {

    $airline_contact_no = $this->custom_db->single_table_records('airline_contact_numbers', '*', array('airline_code' => $return_segment_details[0]['airline_code']));

// debug($airline_contact_no);exit;

    if (isset($airline_contact_no['data'][0])) {

        $airline_number = '<span><img style="vertical-align:middle" src=' . base_url().SYSTEM_IMAGE_DIR . 'phone.png /><span style="font-size:16px;color:#1FB53A;vertical-align:middle;font-weight: 600;"> &nbsp;' . $airline_contact_no['data'][0]['phone_number'] . '</span></span> ';

    } else {

        $airline_number = '';

    }

    if ($booking_transaction_details[1] ['status'] == 'BOOKING_CONFIRMED') {

        $border = 'border-top:2px solid #808080;';

    } else if ($booking_transaction_details[1] ['status'] == 'BOOKING_HOLD') {

        $border = '';

    }

    ?>

    <div style="background:#fff; width:100%; position:relative;margin-top:30px;">

        <table class="pag_brk" cellpadding="0" border-collapse cellspacing="0" width="100%" style="font-size:12px; font-family: 'Open Sans', sans-serif; width:850px; margin:0px auto;background-color:#fff; padding:45px;border-collapse:separate;color: #000;box-shadow: 0 0 5px 0 rgba(0,0,0,.11);border-radius:8px;">

            <tbody>

                <tr>

                    <td colspan="4" style="font-size: 18px;font-weight: 600;text-align: center;padding: 10px 0 12px;">E-Ticket <?php echo $Return; ?></td>

                </tr>

                <tr>

                    <td align="right" colspan="4" style="line-height:26px; border-top:1px solid #1FB53A; border-bottom:1px solid #1FB53A;"><span style="font-size:12px;">Status: </span><strong class="<?php echo booking_status_label($booking_transaction_details[1]['status']) ?>" style=" font-size:14px;">

    <?php

    switch (@$booking_transaction_details[1] ['status']) {

        case 'BOOKING_CONFIRMED' :

            echo 'CONFIRMED';

            break;

        case 'BOOKING_CANCELLED' :

            echo 'CANCELLED';

            break;

        case 'BOOKING_FAILED' :

            echo 'FAILED';

            break;

        case 'BOOKING_INPROGRESS' :

            echo 'INPROGRESS';

            break;

        case 'BOOKING_INCOMPLETE' :

            echo 'INCOMPLETE';

            break;

        case 'BOOKING_HOLD' :

            echo 'HOLD';

            break;

        case 'BOOKING_PENDING' :

            echo 'PENDING';

            break;

        case 'BOOKING_ERROR' :

            echo 'ERROR';

            break;

    }

    ?></strong></td>

                </tr>

                <tr>



                   

                    <td style="padding:10px 0"><img style="width:60px;" src="<?= base_url().SYSTEM_IMAGE_DIR . 'airline_logo/' . $return_segment_details[0]['airline_code'] . '.gif' ?>" /></td>

                    <td style="padding:10px 0; line-height:25px;"><span style="display: block;border-right: 1px solid #999;"><span style="font-size:14px;"><?= @$return_segment_details[0]['airline_name'] ?></span><br><?php echo $airline_number ?></span></td>

                    <td style="padding:10px 0; padding-left: 10%; line-height:25px;"><span style="font-size:14px;">Travel Free Travels customer service</span><br><span><img style="vertical-align:middle" src="<?= base_url().SYSTEM_IMAGE_DIR . 'envelope.png' ?>" /> <span style="font-size:16px;color:#2196f3;vertical-align:middle;font-weight: 600;"> &nbsp;<?= $this->entity_domain_mail ?></span></span></td>







    <?php if ((!empty($return_gds_pnr)) || (!empty($return_airline_pnr))) { ?>

                        <td style="padding:10px 0;text-align: center;">

                            <span style="font-size:14px; border:2px solid #808080; display:block">

        <?php if ($booking_transaction_details[1] ['status'] == 'BOOKING_CONFIRMED') { ?>

                                    <span style="color:#2196f3;padding:5px; display:block">AIRLINE PNR</span>

                                    <span style="font-size:26px;line-height:35px;padding-bottom: 5px;display:block;font-weight: 600;"><?= @$return_airline_pnr ?></span>

        <?php } ?>

                                <?php if (($booking_transaction_details[1] ['status'] == 'BOOKING_CONFIRMED' || $booking_transaction_details[1] ['status'] == 'BOOKING_HOLD') && (!empty($return_gds_pnr))) { ?>

                                    <span style="<?php echo $border; ?>display:block; padding:5px;">GDS PNR:  <?= @$return_gds_pnr ?></span>

                                <?php } ?>

                            </span></td>

                            <?php } ?>

                </tr>

                <tr>

                    <td colspan="4" style="border:1px solid #2196f3; padding:0;">

                        <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">

                            <tbody>

                                <tr>

                                    <td colspan="2" style="background-color:#2196f3; color:#fff"><img style="vertical-align:middle" src="<?=base_url().SYSTEM_IMAGE_DIR . 'flight.png' ?>"> &nbsp;<span style="vertical-align:middle;font-size:13px"><?php echo $Return; ?> Flight Details</span></td>

                                    <td align="right" colspan="2" style="background-color:#2196f3; color:#fff"><span style="font-size:9px">*Please verify flight times with the airlines prior to departure</span></td>

                                </tr>



    <?php

    if (isset($booking_transaction_details) && $booking_transaction_details != "") {

        $checkin_baggage = 0;

        $cabin_baggage = 0;

        $seg_count = count($itinerary_details);

        if ($seg_count == 1) {

            $non_stop = 'Non Stop';

        } else {

            $non_stop = '';

        }

        foreach ($return_segment_details as $segment_details_k => $segment_details_v) {

            $itinerary_details_attributes = json_decode($segment_details_v ['attributes'], true);

            $airline_terminal_origin = @$itinerary_details_attributes['departure_terminal'];

            $airline_terminal_destination = @$itinerary_details_attributes['arrival_terminal'];

            $origin_terminal = '';

            $destination_terminal = '';

            if ($airline_terminal_origin != '') {

                $origin_terminal = 'Terminal ' . $airline_terminal_origin;

            }

            if ($airline_terminal_destination != '') {

                $destination_terminal = 'Terminal ' . $airline_terminal_destination;

            }



            //$checkin_baggage += (int) $segment_details_v['checkin_baggage'];

            //$cabin_baggage += (int) $segment_details_v['cabin_baggage'];  

# Checking CheckIN Baggage





               if (is_int($segment_details_v['checkin_baggage'])) {

                                        @$BaggageSegment_checkin1 .= 'Checkin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . (int) $segment_details_v['checkin_baggage'] . ' Kg & Child :'.(int) $segment_details_v['checkin_baggage'] .' Kg)<br />';

                                        $TotalBaggageCheckIN1 = (int) $segment_details_v['checkin_baggage'];

                                    } else {

                                        @$BaggageSegment_checkin1 .= 'Checkin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . $segment_details_v['checkin_baggage'] . ' & Child :'.$segment_details_v['checkin_baggage'] .')<br />';

                                        $TotalBaggageCheckIN1 = $segment_details_v['checkin_baggage'];

                                    }



# Checking Cabin Baggage



                                    if (is_int($segment_details_v['cabin_baggage'])) {

                                        @$BaggageSegment_cabin1 .= 'Cabin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . (int) $segment_details_v['cabin_baggage'] . ' Kg & Child :'.(int) $segment_details_v['cabin_baggage'].')<br />';

                                        $TotalBaggageCabin1 = (int) $segment_details_v['cabin_baggage'];

                                    } else {

                                        @$BaggageSegment_cabin1 .= 'Cabin Baggage : ' . @$segment_details_v['from_airport_code'] . ' To ' . @$segment_details_v['to_airport_code'] . ' (Adult :' . $segment_details_v['cabin_baggage'] . ' & Child : '.$segment_details_v['cabin_baggage'].')<br />';

                                        $TotalBaggageCabin1 = $segment_details_v['cabin_baggage'];

                                    }



  

            if ($seg_count != 1) {

                $non_stop = ($segment_details_k + 1) . ' Stop';

            }

            if (valid_array($segment_details_v) == true) {

                ?>

                                            <tr>

                                                <td style="background-color:#d9d9d9; color:#555555"><span style="color:#006f8e">Flight <?php echo $segment_details_v['segment_indicator']; ?></span></td>

                                                <td style="background-color:#d9d9d9; color:#555555"><img style="vertical-align:middle" src="<?= base_url().SYSTEM_IMAGE_DIR . 'flight_up.png' ?>">&nbsp;<span style="vertical-align:middle">Departing</span></td>

                                                <td style="background-color:#d9d9d9; color:#555555"><img style="vertical-align:middle" src="<?= base_url().SYSTEM_IMAGE_DIR . 'flight_down.png' ?>">&nbsp;<span style="vertical-align:middle">Arriving</span></td>

                                                <td style="background-color:#d9d9d9; color:#555555">&nbsp;</td>

                                            </tr>

                                            <tr>

                                                <td><span><?= @$segment_details_v['airline_name'] ?><br><?php echo $segment_details_v['airline_code'] . ' - ' . $segment_details_v['flight_number']; ?><br>Cabin: <?php echo $booking_details['cabin_class']; ?></span></td>

                                                <td><span><strong><?= @$segment_details_v['from_airport_name'] ?>(<?= @$segment_details_v['from_airport_code'] ?>)</strong><br><?php echo date("D, d M Y", strtotime($segment_details_v['departure_datetime'])) . ", " . date("h:i A", strtotime($segment_details_v['departure_datetime'])); ?><br><?php echo $origin_terminal; ?></span></td>

                                                <td><span><strong><?= @$segment_details_v['to_airport_name'] ?>(<?= @$segment_details_v['to_airport_code'] ?>)</strong><br><?php echo date("D, d M Y", strtotime($segment_details_v['arrival_datetime'])) . ", " . date("h:i A", strtotime($segment_details_v['arrival_datetime'])); ?><br><?php echo $destination_terminal; ?></span></td>

                                                <td><span style="display:block;border-left: 1px solid #999; padding-left:10%"><?php echo $non_stop; ?><br><?php echo $segment_details_v['total_duration']; ?><br><?php echo $segment_details_v['is_refundable'] ?></span></td>

                                            </tr>

                <?php

            }

        }

    }

    ?>

                            </tbody>

                        </table>

                    </td>

                </tr>

                <tr><td style="line-height:15px;padding:0;">&nbsp;</td></tr>

                <tr>

                    <td colspan="4" style="border:1px solid #666666; padding:0;">

                        <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">

                            <tbody>



                                <tr>

                                    <td colspan="4" style="background-color:#666666; color:#fff"><img style="vertical-align:middle" src="<?=base_url().SYSTEM_IMAGE_DIR . 'people_group.png' ?>"> &nbsp;<span style="vertical-align:middle;font-size:13px;">Passenger(s) Details</span></td>

                                </tr>



                                <tr>

                                    <td style="background-color:#d9d9d9; color:#555555"><span>Sr No.</span></td>

                                    <td style="background-color:#d9d9d9; color:#555555"><span>Passenger(s) Name</span></td>
 <td style="background-color:#d9d9d9; color:#555555"><span>Passenger(s) Nationality</span></td>
                                    <td style="background-color:#d9d9d9; color:#555555"><span>Type</span></td>

                                    <td style="background-color:#d9d9d9; color:#555555"><span>E-ticket No</span></td>

                                </tr>

    <?php

    $booking_transaction_details_value = $booking_transaction_details [1];

    // debug($booking_transaction_details_value);exit;

    if (isset($booking_transaction_details_value ['booking_customer_details'])) {

        foreach ($booking_transaction_details_value ['booking_customer_details'] as $cus_k => $cus_v) {

            if (strtolower($cus_v['passenger_type']) == 'infant') {

                $pass_name = $cus_v['title'] . '.' . $cus_v['first_name'] . '  ' . $cus_v['last_name'];

            } else {

                $pass_name = $cus_v['title'] . '.' . $cus_v['first_name'] . '  ' . $cus_v['last_name'];

            }

            ?>

                                        <tr>

                                            <td><span><?php echo ($cus_k + 1); ?></span></td>

                                            <td><span><strong><?php echo $pass_name; ?></strong</span></td>
                                            <td><span><?php echo ucfirst($cus_v['passenger_nationality']) ?></span></td>

                                            <td><span><?php echo ucfirst($cus_v['passenger_type']) ?></span></td>

                                            <td><span><?= @$cus_v['TicketNumber']; ?></span></td>

                                        </tr>



            <?php

        }

    }

    ?>

                            </tbody>

                        </table>

                    </td>

                </tr>

                <tr><td style="line-height:15px;padding:0;">&nbsp;</td></tr>

                <tr>

                    <td colspan="4" style="padding:0;">

                        <table cellspacing="0" cellpadding="5" width="100%" style="font-size:12px; padding:0;">

                            <tbody>

                                <tr>



                                    <td style="padding:0; vertical-align:top">

                                        <table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #9a9a9a;font-size:12px; padding:0;">

                                            <tbody>

                                                <tr>

                                                    <td colspan="4" style="border-bottom:1px solid #ccc"><span style="font-size:13px">Flight Inclusions</span></td>

                                                </tr>

                                              <tr>



    <?php if ($TotalBaggageCheckIN1 != '' && $TotalBaggageCheckIN1 != 0) { ?>

                                                        <td colspan="4"><span><?php echo $BaggageSegment_checkin1; ?></span></td>



    <?php } else {

        ?>

                                                        <td colspan="4"><span>Checkin Baggage : Allowed as per the Airline Policy</span></td>

                                                    <?php } ?>

                                                </tr>

                                                <tr>



                                                    <?php if ($TotalBaggageCabin1 != '' && $TotalBaggageCabin1 != 0) { ?>

                                                        <td colspan="4"><span><?php echo $BaggageSegment_cabin1; ?></span></td>





    <?php } else {

        ?>

                                                        <td colspan="4"><span>Cabin Baggage :  Allowed as per the Airline Policy</span></td>

    <?php } ?>

                                                </tr>







                                                <tr>

                                                    <td colspan="4" style="border-top:1px solid #ccc"><span style="font-size:10px; color:#666; line-height:20px;">* Flight inclusions are subject to change with Airlines.</span></td>

                                                </tr>

                                            </tbody>

                                        </table>

                                    </td>

                                </tr>

    <?php if (isset($booking_transaction_details_value['extra_service_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']) == true) { ?>

                                    <tr>

                                        <td style="padding:10px 0 0 0;vertical-align:top">

                                            <table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #9a9a9a;font-size:12px; padding:0;">

                                                <tbody>

                                                    <tr>

                                                        <td colspan="4" style="border-bottom:1px solid #ccc"><span style="font-size:13px">Flight Extra Information</span></td>

                                                    </tr>

        <?php

        if (isset($booking_transaction_details_value['extra_service_details']['baggage_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['baggage_details']) == true) {

            $baggage_details = $booking_transaction_details_value['extra_service_details']['baggage_details'];

            foreach ($baggage_details['baggage_source_destination_label'] as $bag_lk => $bag_lv) {

                ?>

                                                            <tr><td colspan="4" style="background-color:#d9d9d9; color:#555555"><span><?php echo 'Extra Baggage Information'; ?> ( <?= $bag_lv ?> )</span></td></tr>

                                                            <?php

                                                            foreach ($baggage_details['details'] as $bag_k => $bag_v) {

                                                                foreach ($bag_v as $bd_k => $bd_v) {

                                                                    // debug($bag_lv);exit;

                                                                    if ($bd_v['from_airport_code'] . '-' . $bd_v['to_airport_code'] == $bag_lv) {

                                                                        ?>

                                                                        <tr><td colspan="4"><?= $bd_v['description'] ?> ( <?= $bag_v[0]['pax_name'] ?> )</td></tr>

                                                                    <?php

                                                                    }

                                                                }

                                                            }

                                                        }

                                                    }

                                                    // debug($booking_transaction_details_value);exit;

                                                    if (isset($booking_transaction_details_value['extra_service_details']['meal_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['meal_details']) == true) {

                                                        $meal_details = $booking_transaction_details_value['extra_service_details']['meal_details'];

                                                        $meal_type = end($meal_details['details']);



                                                        $meal_type = $meal_type[0]['type'];

                                                        if ($meal_type == 'static') {

                                                            $meal_type_label = 'Meal Preference';

                                                        } else {

                                                            $meal_type_label = 'Meal Information';

                                                        }

                                                        foreach ($meal_details['meal_source_destination_label'] as $meal_lk => $meal_lv) {

                                                            ?>

                                                            <tr><td colspan="4" style="background-color:#d9d9d9; color:#555555"><span><?php echo $meal_type_label; ?> ( <?= $bag_lv ?> )</span></td></tr>

                                                            <?php

                                                            foreach ($meal_details['details'] as $meal_k => $meal_v) {

                                                                foreach ($meal_v as $md_k => $md_v) {



                                                                    if ($md_v['from_airport_code'] . '-' . $md_v['to_airport_code'] == $bag_lv) {

                                                                        ?>

                                                                        <tr><td colspan="4"><?= $md_v['description'] ?> ( <?= $meal_v[0]['pax_name'] ?> )</td></tr>

                                                                    <?php

                                                                    }

                                                                }

                                                            }

                                                        }

                                                    }

                                                    if (isset($booking_transaction_details_value['extra_service_details']['seat_details']) == true && valid_array($booking_transaction_details_value['extra_service_details']['seat_details']) == true) {

                                                        $seat_details = $booking_transaction_details_value['extra_service_details']['seat_details'];

                                                        $seat_type = end($seat_details['details']);

                                                        $seat_type = $seat_type[0]['type'];

                                                        if ($seat_type == 'static') {

                                                            $seat_type_label = 'Seat Preference';

                                                        } else {

                                                            $seat_type_label = 'Seat Information';

                                                        }

                                                        foreach ($seat_details['seat_source_destination_label'] as $seat_lk => $seat_lv) {

                                                            ?>

                                                            <tr><td colspan="4" style="background-color:#d9d9d9; color:#555555"><span><?php echo $seat_type_label; ?> ( <?= $seat_lv ?> )</span></td></tr>

                                                            <?php

                                                            foreach ($seat_details['details'] as $seat_k => $seat_v) {

                                                                // debug($seat_lv);exit;

                                                                foreach ($seat_v as $sd_k => $sd_v) {

                                                                    if ($sd_v['from_airport_code'] . '-' . $sd_v['to_airport_code'] == $seat_lv) {

                                                                        $seat_description = trim($sd_v['description']);

                                                                        if (empty($seat_description) == true) {

                                                                            $seat_description = trim($sd_v['code']);

                                                                        }

                                                                        ?>

                                                                        <tr><td colspan="4"><?= $seat_description ?> ( <?= $seat_v[0]['pax_name'] ?> )</td></tr>

                        <?php }

                    }

                }

            }

        } ?>

                                                </tbody>

                                            </table>

                                        </td>



                                    </tr>

    <?php } ?>

                            </tbody>

                        </table>

                    </td>

                </tr>

                <tr><td style="line-height:15px;padding:0;">&nbsp;</td></tr>

                <tr><td colspan="4" style="border-bottom:1px solid #999999;padding-bottom:15px"><span style="font-size:13px; color:#555;">Customer Contact Details | E-mail : <?= $booking_details['email'] ?> | Contact No : <?= $booking_details['phone'] ?></span></td></tr>

                <tr><td style="line-height:15px;padding:0;">&nbsp;</td></tr>

                <tr>

                    <td colspan="4"><span style="line-height:20px; font-size:13px;">Important Information</span></td></tr>

                <tr>

                    <td colspan="4" style="border-bottom:1px solid #999999; line-height:20px; font-size:12px; color:#555;font-weight: 600;">

                    <?php echo $data['terms_conditions']; ?>

                     

                        <!-- <ul style="padding-bottom:10px">

                            <li>All Guests, including children and infants, must present valid identification at check-in.</li>

                            <li>Check-in begins 2 hours prior to the flight for seat assignment and closes 45 minutes prior to the scheduled departure.</li>

                            <li>Carriage and other services provided by the carrier are subject to conditions of carriage, which are hereby incorporated by reference. These conditions may be obtained from the issuing carrier.</li>

                            <li>In case of cancellations less than 6 hours before departure please cancel with the airlines directly. We are not responsible for any losses if the request is received less than 6 hours before departure.</li>

                            <li>Please contact airlines for Terminal Queries.</li>

                            <li>Free Baggage Allowance: Checked-in Baggage = 15kgs in Economy class.</li>

                            <li>Partial cancellations are not allowed for Round-trip Fares</li>

                            <li>Changes to the reservation will result in the above fee plus any difference in the fare between the original fare paid and the fare for the revised booking.</li>

                            <li>In case of cancellation of a booking, made by a Go channel partner, refund has to be collected from that respective Go Channel.</li>

                            <li>The No Show refund should be collected within 15 days from departure date.</li>

                            <li>If the basic fare is less than cancellation charges then only statutory taxes would be refunded.</li>

                            <li>We are not be responsible for any Flight delay/Cancellation from airline's end.</li>

                            <li>Kindly contact the airline at least 24 hrs before to reconfirm your flight detail giving reference of Airline PNR Number.</li>

                            <li>We are a travel agent and all reservations made through our website are as per the terms and conditions of the concerned airlines. All modifications,cancellations and refunds of the airline tickets shall be strictly in accordance with the policy of the concerned airlines and we disclaim all liability in connection thereof.</li>

                        </ul> -->

                    </td>

                </tr>

                <!-- <tr>

                    <td colspan="4" align="right" style="padding-top:10px"><?php echo strtoupper($data['domainname']) ?><br>ContactNo :  <?php echo $data['phone_code'] ?><?php echo $data['phone'] ?><br><?php echo $data['address'] ?></td>

                </tr> -->
                <tr>
                                        <td colspan="4" align="right"
                                            style="padding-top:10px;font-size:13px;line-height:20px;">
                                            <img src="https://www.travelsoho.com/travel-free-travels/extras/system/template_list/template_v3/images/order1.jpg"
                                        alt="" align="left" style="width:120px;">
                                            <?=strtoupper($data['domainname'])?><br>Phone :
                                            <?=$data['voucher_phone']?><br>Email :
                                            <?=$data['voucher_email']?><br>Address :
                                            <?=STATIC_ADDRESS;?><br><?=STATIC_COUNTRY;?></td>
                                    </tr>

            </tbody>

        </table>

       

    </div>

<?php } ?>

</div>

<?php 

    



    if(!$email_send_check){

    ?>

 <div class="dwnprnt" style="text-align: center;">

    <a href="<?php echo base_url();?>" class="btn btn-grn btfl">Back</a>

    <?php if($booking_transaction_details[0] ['status']=="BOOKING_CONFIRMED" || $booking_transaction_details[1] ['status']=="BOOKING_CONFIRMED"){?>

    <button type="button" onClick="printDiv('printpage')" class="btn btn-orn btfl">Print</button>

    <?php }?>

</div>

<?php } ?>

<script type="text/javascript">

    function printDiv(divName) {

     var printContents = document.getElementById(divName).innerHTML;

     var originalContents = document.body.innerHTML;



     document.body.innerHTML = printContents;

     document.title = "Flight Ticket-Travel Free Travels";

     window.print();



     document.body.innerHTML = originalContents;

}

</script>