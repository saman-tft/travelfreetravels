<table class="table table-condensed table-bordered" id="b2c_report_hotel_table">
    <thead>
        <tr>
            <th style="font-weight: bold;">Sno</th>
            <th style="font-weight: bold;" colspan="3">Reference No</th>
            <th style="font-weight: bold;" colspan="3">Status</th>
            <th style="font-weight: bold;" colspan="4">Lead Pax Details</th>
            <th style="font-weight: bold;" colspan="2">PNR</th>
            <th style="font-weight: bold;">From</th>
            <th style="font-weight: bold;">To</th>
            <th style="font-weight: bold;" colspan="3">Booked Via</th>
            <th style="font-weight: bold;" colspan="3">Financial Remarks</th>
            <th style="font-weight: bold;" colspan="3">Other Remarks</th>
            <th style="font-weight: bold;" colspan="4">Creation Details</th>
            <th style="font-weight: bold;" colspan="2">Supplier Name</th>
        </tr>
        <tr>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($export_data)) {
            $i = 1;
            foreach ($export_data as $parent_k => $parent_v) {
                extract($parent_v);
                $booking_attributes_remarks = json_decode($attributes);
                $attributes = json_decode($parent_v['booking_transaction_details'][0]['attributes'], true);
        ?>
                <tr>
                    <td><?php echo ($i++) ?></td>
                    <td colspan="3"><?php echo $app_reference; ?></td>
                    <td colspan="3"><?php echo $status ?></td>
                    <td colspan="4"><?php echo $lead_pax_name . '<br/>' . $email . "<br/>" . $phone; ?></td>
                    <td colspan="2"><?php echo @$pnr ?></td>
                    <td><?php echo $from_loc ?></td>
                    <td><?php echo $to_loc ?></td>
                    <td colspan="3"><?php echo $agency_name; ?></td>
                    <td colspan="3"><?php echo @$booking_attributes_remarks->fin_remarks; ?></td>
                    <td colspan="3"><?php echo @$booking_attributes_remarks->oth_remarks; ?></td>
                    <td colspan="4">
                        Added At: <?php echo @$booking_attributes_remarks->remarks_updated; ?><br />
                        Financial Remarks By <?php echo @$booking_attributes_remarks->fin_remarks_user_name; ?><br />
                        Other Remarks By <?php echo @$booking_attributes_remarks->oth_remarks_user_name; ?>
                    </td>
                    <td colspan="2"><?php echo @flight_supplier_name($booking_source); ?></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>
<br /><br /><br />
<hr />
<br /><br /><br />
<table class="table table-condensed table-bordered" id="b2c_report_hotel_table">
    <thead>
        <tr>
            <th style="font-weight: bold;">Sno</th>
            <th style="font-weight: bold;" colspan="3">Reference No</th>
            <th style="font-weight: bold;">Type</th>
            <th style="font-weight: bold;" colspan="2">BookedOn</th>
            <th style="font-weight: bold;" colspan="2">Travel date</th>
            <th style="font-weight: bold;">Comm.Fare</th>
            <th style="font-weight: bold;">Commission</th>
            <th style="font-weight: bold;">Tax</th>
            <th style="font-weight: bold;">Segment Discount</th>
            <th style="font-weight: bold;">TDS</th>
            <th style="font-weight: bold;">Admin NetFare</th>
            <th style="font-weight: bold;">Admin Markup</th>
            <th style="font-weight: bold;">GST</th>
            <th style="font-weight: bold;">Agent Commission</th>
            <th style="font-weight: bold;">Agent TDS</th>
            <th style="font-weight: bold;">Agent Net Fare</th>
            <th style="font-weight: bold;">Agent Markup</th>
            <th style="font-weight: bold;">TotalFare</th>
        </tr>
        <tr>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($export_data)) {
            $i = 1;
            foreach ($export_data as $parent_k => $parent_v) {
                extract($parent_v);
                $booking_attributes_remarks = json_decode($attributes);
                $attributes = json_decode($parent_v['booking_transaction_details'][0]['attributes'], true);
        ?>
                <tr>
                    <td><?php echo ($i++) ?></td>
                    <td colspan="3"><?php echo $app_reference; ?></td>
                    <td><?php echo $trip_type_label ?></td>
                    <td colspan="2"><?php echo date('d-m-Y H:i:s A', strtotime($created_datetime)) ?></td>
                    <td colspan="2"><?php echo date('d-m-Y', strtotime($journey_start)) ?></td>
                    <td><?php echo $fare ?></td>
                    <td><?php echo $net_commission ?></td>
                    <td><?php
                        if ($booking_source == "PTBSID0000000021") {
                            $trans_attributes = json_decode($booking_transaction_details[0]['attributes'], true);
                            $currency_obj = new Currency(array(
                                'module_type' => 'flight',
                                'from' => $trans_attributes['Fare']['Currency'],
                                'to' => 'NPR',
                            ));
                            echo round(get_converted_currency_value($currency_obj->force_currency_conversion($attributes['Fare']['Tax'])));
                        } else {
                            if ($booking_source == "PTBSID0000000002") {
                                $trans_attributes = json_decode($booking_transaction_details[0]['attributes'], true);
                                $currency_obj = new Currency(array(
                                    'module_type' => 'flight',
                                    'from' => $trans_attributes['Fare']['Currency'],
                                    'to' => 'NPR',
                                ));
                                echo round(get_converted_currency_value($currency_obj->force_currency_conversion($attributes['Fare']['Tax'])));
                            } else {
                                echo $attributes['Fare']['Tax'];
                            }
                        }
                        ?></td>
                    <td><?php echo $segment_discount ?></td>
                    <td><?php echo $net_commission_tds ?></td>
                    <td><?php echo $net_fare ?></td>
                    <td><?php echo $admin_markup ?></td>
                    <td><?php echo $gst ?></td>
                    <td><?php echo $agent_commission ?></td>
                    <td><?php echo $agent_tds ?></td>
                    <td><?php echo $agent_buying_price - $agent_markup ?></td>
                    <td><?php echo $agent_markup ?></td>
                    <td><?php echo ($grand_total - ($segment_discount + $reward_amount)) ?></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>