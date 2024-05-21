<div style="font-size: large;">
    <?php
    foreach ($data as $mainkey => $mainvalue) {
        $booking_details = $mainvalue['booking_details'][0];
        $convience_fee = $booking_details['convinence_value'];
        $booking_itinerary_details = $booking_details['booking_itinerary_details'];
        $booking_transaction_details = $booking_details['booking_transaction_details'];
        $itinerary_details = $booking_details['booking_itinerary_details'];

        if ($booking_details['is_domestic'] == true && count($booking_transaction_details) == 2) {
            $onward_segment_details = array();
            $return_segment_details = array();
            $segment_indicator_arr = array();
            $segment_indicator_sort = array();

            foreach ($itinerary_details as $key => $key_sort_data) {
                $segment_indicator_sort[$key] = $key_sort_data['origin'];
            }
            array_multisort($segment_indicator_sort, SORT_ASC, $itinerary_details);

            foreach ($itinerary_details as $k => $sub_details) {
                $segment_indicator_arr[] = $sub_details['segment_indicator'];
                $count_value = array_count_values($segment_indicator_arr);

                if ($count_value[1] == 1) {
                    $onward_segment_details[] = $sub_details;
                } else {
                    $return_segment_details[] = $sub_details;
                }
            }
        }
        if (isset($return_segment_details)) {
            $retur_fare_details = json_decode($booking_transaction_details[1]['attributes'], True);
        }

        $fare_details = json_decode($booking_transaction_details[0]['attributes'], True);
        $BaseFare = $fare_details['Fare']['BaseFare'] + @$retur_fare_details['Fare']['BaseFare'];
        $Tax = $fare_details['Fare']['Tax'] + @$retur_fare_details['Fare']['Tax'];
        $GST = $booking_transaction_details[0]['gst'] + @$booking_transaction_details[1]['gst'];
        $i = 0;
        $cum_total = 0;
    ?>
        <?php foreach ($booking_transaction_details as $t_key => $t_value) :
            $i++;
        ?>
            <table>
                <thead>
                    <tr>
                        <th>
                            <h3>Sale Invoice</h3>
                        </th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>
                            <h3>Date: <?php echo date('d-m-Y') ?></h3>
                        </th>
                    </tr>
                </thead>
            </table>
            <br>
            <br>
            <br>
            <br>
            <table>
                <tr>
                    <td>
                        <b>From</b><br>
                        <?= $admin_details[$mainkey]['domainname'] ?><br>
                        Phone: <?= $admin_details[$mainkey]['phone'] ?><br>
                        <?php if ($admin_details[$mainkey]['state']) : ?>
                            State: <?= $admin_details[$mainkey]['state'] ?><br>
                        <?php endif; ?>
                        <?= $admin_details[$mainkey]['address'] ?>,
                        <br>
                        <br>
                        <b>Travel Free Travels Gst No: </b>
                        <br>
                        <b>User Gst No: </b> <?php
                                                $gstnumber = json_decode($booking_details['gst_details'], true);
                                                echo $gstnumber['gst_number'];


                                                ?><!-- <?= domain_gst_number() ?> --><!-- agent gst number-->
                    </td>
                    <td>
                        <b>To</b><br>
                        <table>
                            <tbody>
                                <tr>
                                    <td><b>User name: </b> <?php echo $booking_details['lead_pax_name']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Phone: </b> <?php echo $booking_details['lead_pax_phone_number']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Email: </b><?php echo $booking_details['lead_pax_email'] ?></td>
                                </tr>
                                <tr>
                                    <td><b>Address: </b><?php echo $booking_details['cutomer_address'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <b>App Ref No: </b><?php echo $booking_details['app_reference']; ?><br>
                        <b>PNR: </b><?php echo $t_value['pnr']; ?><br>
                        <b>Date: </b> <?php echo @$booking_details['created_datetime']; ?>
                        <br>
                        <b>Invoice Number: </b> <?php echo @date('m/yy', strtotime($booking_details['created_datetime'])); ?>/<?php
                                                                                                                                $length = 4;
                                                                                                                                echo str_pad($booking_details['origin'], $length, "0", STR_PAD_LEFT);
                                                                                                                                ?><br>
                        <b>Status: </b><?php echo $booking_details['status'] ?>
                    </td>
                </tr>
            </table>
            <br>
            <br>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>
                            <h4>S.No</h4>
                        </th>
                        <th>
                            <h4>Ticket No</h4>
                        </th>
                        <th>
                            <h4>PNR</h4>
                        </th>
                        <th>
                            <h4>Sector</h4>
                        </th>
                        <th>
                            <h4>Pax Name</h4>
                        </th>
                        <th>
                            <h4>Pax Type</h4>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($booking_transaction_details) == 2) {
                        $itinerary_details = array();
                        $itinerary_details = $onward_segment_details;
                    }
                    if (isset($t_value['booking_customer_details'])) {
                        foreach ($t_value['booking_customer_details'] as $key => $cus_details) {
                    ?>
                            <tr>
                                <td><?php echo $key + 1; ?></td>
                                <?php
                                if ($cus_details['ticket_no'] == '') {
                                    $cus_details['ticket_no'] = $t_value['booking_customer_details'][0]['ticket_no'];
                                }
                                ?>
                                <td><?php echo $cus_details['ticket_no']; ?></td>
                                <td><?php echo $t_value['pnr']; ?></td>
                                <td><?php echo ucfirst($t_value['segment_details'][1][$t_key]['from_airport_code']) . '-' . ucfirst($t_value['segment_details'][1][$t_key]['to_airport_code']); ?></td>
                                <td><?php echo $cus_details['title'] . ' ' . $cus_details['first_name'] . ' ' . $cus_details['last_name']; ?></td>
                                <td><?php echo $cus_details['passenger_type']; ?></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <br>
            <br>
            <h2>Fare Details</h2>
            <?php
            $record_num = end($this->uri->segment_array());
            if ($record_num == '4') {
                $booking_transaction_details_tkey = json_decode($booking_transaction_details[$t_key]['attributes'], True);
                $BaseFare = $booking_transaction_details_tkey['Fare']['BaseFare'];
                $Tax = $booking_transaction_details_tkey['Fare']['Tax'];
                $AgentComission = $t_value['agent_commission'];
                $AgentTdsOnCommission = $t_value['agent_tds'];
                $Gst  = $t_value['gst'];
                $Admin_Markup = $t_value['admin_markup'];
                $Agent_Markup = $t_value['agent_markup'];
                $Total_Fare = $t_value['total_fare'];
                $gst_details = $GLOBALS['CI']->custom_db->single_table_records('gst_master', '*', array('module' => 'flight'));
                $markup = $Admin_Markup;
                $markup_gst = 0;
                if ($gst_details['status'] == true) {
                    if ($gst_details['data'][0]['gst'] > 0) {
                        $markup = ($Admin_Markup / (100 + $gst_details['data'][0]['gst'])) * 100;
                        $markup_gst = $Admin_Markup - $markup;
                    }
                }
                $ugst = $Gst + $markup_gst;
                $AgentNetfare = ($Total_Fare + $Admin_Markup + $AgentTdsOnCommission - $AgentComission);
                $total = ($AgentNetfare + $Agent_Markup + $AgentComission + $convience_fee + $Gst - $AgentTdsOnCommission - $booking_details['discount']);
            } elseif ($record_num == '3') {
                $booking_transaction_details_tkey = json_decode($booking_transaction_details[$t_key]['attributes'], True);
                $BaseFare = $booking_transaction_details_tkey['Fare']['BaseFare'];
                $Tax = $booking_transaction_details_tkey['Fare']['Tax'];
                $AgentComission = $booking_transaction_details_tkey['Fare']['AgentCommission'];
                $AgentTdsOnCommission = $t_value['agent_tds'];
                $Gst  = $t_value['gst'];
                $Admin_Markup = $t_value['admin_markup'];
                $Agent_Markup = $t_value['agent_markup'];
                $Total_Fare = $t_value['total_fare'];
                $AgentNetfare = ($Total_Fare + $Admin_Markup + $AgentTdsOnCommission - $AgentComission);
                $total = ($AgentNetfare + $Agent_Markup + $AgentComission - $AgentTdsOnCommission + $Gst);
            } else {
                echo "Unauthorized!!!";
                exit;
            }
            if ($t_key == 0 && $t_value['status_description'] != 0) {
                $convDetailsValue = 0;
                $convDetailsValue = (float) $t_value['status_description'];
            }
            $cum_total += $total;
            if ($record_num == '4') {
            ?>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Base Fare</td>
                            <td><?php echo number_format($BaseFare, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Tax</td>
                            <td><?php echo number_format($Tax, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Gross Total</td>
                            <td><?php echo number_format($Total_Fare, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Markup</td>
                            <td><?php echo number_format($markup, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Service Charge Collected</td>
                            <td><?php echo number_format($convience_fee, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>GST</td>
                            <td><?php echo number_format($ugst, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td><?php echo number_format($booking_details['discount'], 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Net Amount</td>
                            <td><?php echo number_format($total, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td><b>Round Off</b></td>
                            <td><b><?= round($total) ?></b></td>
                        </tr>
                        <tr></tr>
                    </tbody>
                </table>
            <?php
            } elseif ($record_num == '3') {
            ?>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Base Fare</td>
                            <td><?php echo number_format($BaseFare, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Tax</td>
                            <td><?php echo number_format($Tax, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Less: Commission Earned</td>
                            <td>-<?php echo number_format($AgentComission, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Gross Total</td>
                            <td><?php echo number_format($Total_Fare, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Add: Markup</td>
                            <td><?php echo number_format($Admin_Markup + $Agent_Markup, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Add: Service Charge Collected</td>
                            <td><?php echo number_format($convience_fee, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Add: GST on service charge</td>
                            <td><?php echo number_format($Gst, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td>Add: TDS Deducted</td>
                            <td><?php echo number_format($AgentTdsOnCommission, 2, '.', ''); ?></td>
                        </tr>

                        <tr>
                            <td>Net Amount</td>
                            <td><?php echo number_format($total, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td><b>Round Off</b></td>
                            <td><b><?= round($total) ?></b></td>
                        </tr>
                    </tbody>
                </table>
            <?php
            } else {
                echo ('Unauthorized!!!');
                exit;
            }
            ?>
            <br>
            <br>
            <h2><b>Total Amount in Words: <?php echo getIndianCurrency($total); ?></b></h2>
            <br>
            <br>
            <h2>Terms &amp; Conditions</h2>
            <table>
                <tbody>
                    <tr>
                        <td>* All Cases Disputes are subject to Nepal Jurisdiction.</td>
                    </tr>
                    <tr>
                        <td>* Refunds Cancellations are subject to Airline's approval.</td>
                    </tr>
                    <tr>
                        <td>* Kindly check all details carefully to avoid unnecessary complications.</td>
                    </tr>
                    <tr>
                        <td>* CHEQUE : Must be drawn in favour of "<?= $admin_details[0]['domainname'] ?>".</td>
                    </tr>
                    <tr>
                        <td>* LATE PAYMENT : Interest @ 24% per annum will be charged on all outstanding bills after due date.</td>
                    </tr>
                    <tr>
                        <td>* Service charges as included above are to be collected from the customers on our behalf.</td>
                    </tr>
                    <tr>
                        <td>* Kindly check all details carefully to avoid un-necessary complications.</td>
                    </tr>
                    <tr>
                        <td>* Any Disputes or variations should be brought to our notice with in 15 days of the invoice.</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <br>
            <?php if ($i == count($booking_transaction_details)) { ?>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>Total Amount: </b></td>
                            <td><?php echo number_format($cum_total, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td><b>Convenience Fee: </b></td>
                            <td><?php echo number_format($convDetailsValue, 2, '.', ''); ?></td>
                        </tr>
                        <tr>
                            <td><b>Grand Total: </b></td>
                            <td><?php echo number_format($convDetailsValue + $cum_total, 2, '.', ''); ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?> <br><br><br>
            <?php } ?>
            <?php for ($j = 0; $j < (14 - count($t_value['booking_customer_details'])); $j++) { ?><br><?php } ?>
        <?php endforeach; ?>
    <?php
    }
    ?>
</div>
<?php
// function getIndianCurrency($number)
// {
//     $decimal = round($number - ($no = floor($number)), 2) * 100;
//     $hundred = null;
//     $digits_length = strlen($no);
//     $i = 0;
//     $str = array();
//     $words = array(
//         0 => '', 1 => 'One', 2 => 'Two',
//         3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
//         7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
//         10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
//         13 => 'Thirteen', 14 => 'fourteen', 15 => 'Fifteen',
//         16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
//         19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
//         40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
//         70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
//     );
//     $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
//     while ($i < $digits_length) {
//         $divider = ($i == 2) ? 10 : 100;
//         $number = floor($no % $divider);
//         $no = floor($no / $divider);
//         $i += $divider == 10 ? 1 : 2;
//         if ($number) {
//             $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
//             $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
//             $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
//         } else
//             $str[] = null;
//     }
//     $Rupees = implode('', array_reverse($str));
//     $paise = ($decimal) ? ". " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paisa' : '';
//     echo ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
// }
?>