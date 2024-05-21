<?php
// error_reporting(E_ALL);
// debug($export_data);exit;

?>
<table class="table table-condensed table-bordered" id="b2c_report_hotel_table">
		<thead>
		<tr>
			<th>Sl. No.</th>
			<th>Reservation Code</th>
			<th>Package Name</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Passanger Name</th>
			<th>Departure Date</th>
			<th>Number Of Days</th>
			<th>Package Price</th>
			<th>Promocode Amount</th>
			<th>Markup</th>
			<th>Convenience Fee</th>
			<th>VAT</th>
			<th>Total Fare</th>	
			<th>Grand Total</th>
			
			<th>BookedOn</th>
			
		</tr>
		</thead>
		<tbody>
			<?php

				// debug($export_data);exit;
				if(!empty($export_data))
				{
					$i=1;

					foreach ($export_data as $k => $v) {
           // debug($v);exit;
           $pax_name="";
           	if($v['pax_details']){
                       foreach ($v['pax_details'] as  $value){ ?>

                        <?php

                         
                         $pax_name .= $value['pax_last_name']." ".$value['pax_first_name']."<br>";
                         
                      		}
                          
                         }

            $book_attr = json_decode($v['booking_details']['attributes'],TRUE);     
            $attributes=$v['booking_details']['attributes'];

            $attributes=json_decode($attributes,true);

            $aed_attributes=json_decode($v['booking_details']['aed_array']);
                         // debug($aed_attributes);exit;
                $base_fare=0.00;
                if(isset($aed_attributes->aed_basic_price))
                {
                    $base_fare=($aed_attributes->aed_basic_price);
                    $base_fare=str_replace(",", "", $base_fare);
                }
                

                 $markup=0.00;
                if(isset($aed_attributes->aed_markup))
                {
                    $markup=($aed_attributes->aed_markup);
                    $markup=str_replace(",", "", $markup);
                }

                $gst_value=0.00;
                if(isset($aed_attributes->aed_gst_value))
                {
                    $gst_value=($aed_attributes->aed_gst_value);
                    $gst_value=str_replace(",", "", $gst_value);
                }
                $discount_value=0.00;
                if(isset($aed_attributes->aed_discount))
                {
                    $discount_value=($aed_attributes->aed_discount);
                    $discount_value=str_replace(",", "", $discount_value);
                }
                $total_fare=($base_fare+$aed_attributes->aed_convenience_fee)-$discount_value;
            	$package_price=$base_fare-$markup-$gst_value;
            	$package_price=round($package_price);
                $package_price=isset($package_price)? number_format($package_price , 2):0;
                if($v['booking_details']['discount']){
                      // echo number_format($data['booking_details']['discount'],2);
                     // echo isset($data['booking_details']['discount'])? number_format(get_converted_currency_value ( $currency_obj->force_currency_conversion ( $data['booking_details']['discount'] ) ), 2):0;
                      $discount=round($discount_value);  
                    }else{
                      $discount= "NA";
                    }
                     $markup=round($markup);
                    $markup=number_format($markup,2);

                    $conveince_fee=round($aed_attributes->aed_convenience_fee);
                    $conveince_fee=isset($conveince_fee)? number_format($conveince_fee,2):0;
                     $gst_value=round($gst_value);
                    $gst_value= number_format($gst_value,2);
                    $base_fare=round($base_fare);
                	$base_fare=number_format($base_fare, 2);
                	$total=round($total_fare);
						// debug($v);
					
					?>
					<tr>
							 <td><?=$i?></td>
							<td><?=$v['booking_details']['app_reference']?></td>
							<td><?=$v['tours_details']['package_name']?></td>
							<td><?=$book_attr['billing_email']?></td>
							<td><?=$book_attr['passenger_contact']?></td>
							<td><?=$pax_name?></td>
							<td><?=$attributes['departure_date']?></td>
							<td><?=$v['tours_details']['duration']?></td>
							<td><?=$package_price?></td>
							<td><?=$discount?></td>
							<td><?=$markup?></td>
							<td><?=$conveince_fee?></td>
							<td><?=$gst_value?></td>
							<td><?=$base_fare?></td>
							<td><?=number_format($total,2)?></td>
							
							<td><?=changeDateFormat($v['booking_details']['booked_datetime'])?></td>
							<td>Online</td>
							
					</tr>
					<?php
						}
					}
					?>

		</tbody></table>