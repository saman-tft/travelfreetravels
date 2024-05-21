<h1><?php echo $title . date("Y-m-d_H:i:s"); ?></h1>
<?php
if ($module == 'b2c') {
?>
    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th style="font-weight: bold;">Sno</th>
                <th style="font-weight: bold;" colspan="4">Name</th>
                <th style="font-weight: bold;" colspan="2">Mobile</th>
                <th style="font-weight: bold;" colspan="6">Email</th>
                <th style="font-weight: bold;" colspan="2">Pending Reward</th>
                <th style="font-weight: bold;" colspan="4">Created On</th>
                <th colspan="6"></th>
            </tr>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($export_data)) {
                foreach ($export_data as $user_k => $user_v) {
            ?>
                    <tr>
                        <td><?php echo $user_k + 1; ?></td>
                        <td colspan="4"><?php echo $user_v['first_name'] . ' ' . $user_v['last_name']; ?></td>
                        <td colspan="2"><?php echo $user_v['phone']; ?></td>
                        <td colspan="6"><?php echo provab_decrypt($user_v['email']); ?></td>
                        <td colspan="2"><?php echo  $user_v['pending_reward'] ? $user_v['pending_reward'] : 0; ?></td>
                        <td colspan="4"><?php echo $user_v['created_datetime']; ?></td>
                        <td colspan="6"></td>
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
<?php
} elseif ($module == 'b2b') {
?>
    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th style="font-weight: bold;">Sno</th>
                <th style="font-weight: bold;" colspan="3">Agency Name</th>
                <th style="font-weight: bold;" colspan="2">Agency ID</th>
                <th style="font-weight: bold;" colspan="3">Agent Name</th>
                <th style="font-weight: bold;" colspan="2">City/<br />Country</th>
                <th style="font-weight: bold;" colspan="2">Mobile</th>
                <th style="font-weight: bold;" colspan="4">Email</th>
                <th style="font-weight: bold;">Balance</th>
                <th style="font-weight: bold;">Credit Limit</th>
                <th style="font-weight: bold;">Due Amount</th>
                <th style="font-weight: bold;">Deposit Req</th>
                <?php if (is_active_airline_module()) { ?> <th style="font-weight: bold;">Flight</th> <?php } ?>
                <?php if (is_active_hotel_module()) { ?> <th style="font-weight: bold;">Hotel</th> <?php } ?>
                <?php if (is_active_bus_module()) { ?> <th style="font-weight: bold;">Bus</th> <?php } ?>
                <?php if (is_active_transferv1_module()) { ?> <th style="font-weight: bold;">Transfer</th> <?php } ?>
                <?php if (is_active_sightseeing_module()) { ?> <th style="font-weight: bold;">Activity</th> <?php } ?>
                <th style="font-weight: bold;" colspan="2">Created on</th>
            </tr>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($export_data)) {
                $i = 0;
                foreach ($export_data as $user_k => $user_v) {
                    $dep_req = '';
                    if (isset($user_v['dep_req']) == true && isset($user_v['dep_req']['pending']) == true) {
                        $dep_req = intval($user_v['dep_req']['pending']['count']);
                    } else {
                        $dep_req = 0;
                    }
                    $booking_summ = '';
                    if (is_active_airline_module()) {
                        $booking_summ .= '<td>' . intval(@$user_v['booking_summ']['flight']['BOOKING_CONFIRMED']['count']) . '</td>';
                    }
                    if (is_active_hotel_module()) {
                        $booking_summ .= '<td>' . intval(@$user_v['booking_summ']['hotel']['BOOKING_CONFIRMED']['count']) . '</td>';
                    }
                    if (is_active_bus_module()) {
                        $booking_summ .= '<td>' . intval(@$user_v['booking_summ']['bus']['BOOKING_CONFIRMED']['count']) . '</td>';
                    }
                    if (is_active_transferv1_module()) {
                        $booking_summ .= '<td>' . intval(@$user_v['booking_summ']['transfer']['BOOKING_CONFIRMED']['count']) . '</td>';
                    }
                    if (is_active_sightseeing_module()) {
                        $booking_summ .= '<td>' . intval(@$user_v['booking_summ']['sightseeing']['BOOKING_CONFIRMED']['count']) . '</td>';
                    }
            ?>
                    <tr>
                        <td><?php echo ++$i; ?></td>
                        <td colspan="3"><?php echo empty($user_v['agency_name']) == false ? $user_v['agency_name'] : 'Not Added'; ?></td>
                        <td colspan="2"><?php echo provab_decrypt($user_v['uuid']); ?></td>
                        <td colspan="3"><?php echo get_enum_list('title', $user_v['title']) . ' ' . $user_v['first_name'] . ' ' . $user_v['last_name']; ?></td>
                        <td colspan="2"><?php echo get_city_name($user_v['city']); ?>/<br /><?php echo get_country_name($user_v['country_name']); ?></td>
                        <td colspan="2"><?php echo $user_v['phone']; ?></td>
                        <td colspan="4"><?php echo provab_decrypt($user_v['email']); ?></td>
                        <td><?php echo roundoff_number($user_v['balance']); ?></td>
                        <td><?php echo roundoff_number($user_v['credit_limit']); ?></td>
                        <td><?php echo roundoff_number($user_v['due_amount']); ?></td>
                        <td><?php echo $dep_req; ?></td>
                        <?php echo $booking_summ; ?>
                        <td colspan="2"><?php echo $user_v['created_datetime']; ?></td>
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

<?php
} elseif ($module == 'supplier') {
?>
    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th style="font-weight: bold;">Sno</th>
                <th style="font-weight: bold;" colspan="4">Supplier Name</th>
                <th style="font-weight: bold;" colspan="2">Phone</th>
                <th style="font-weight: bold;" colspan="6">Email</th>
                <th style="font-weight: bold;" colspan="3">Country</th>
                <th style="font-weight: bold;" colspan="2">Pin</th>
                <th style="font-weight: bold;" colspan="7">Address</th>
                <th style="font-weight: bold;" colspan="4">Created On</th>
            </tr>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($export_data)) {
                $i = 0;
                foreach ($export_data as $user_k => $user_v) {
            ?>
                    <tr>
                        <td><?php echo ++$i; ?></td>
                        <td colspan="4"><?php echo $user_v['first_name'] . ' ' . $user_v['last_name']; ?></td>
                        <td colspan="2"><?php echo $user_v['phone']; ?></td>
                        <td colspan="6"><?php echo provab_decrypt($user_v['email']); ?></td>
                        <td colspan="3"><?php echo get_country_name($user_v['country_name']) ? get_country_name($user_v['country_name']) : 'Malaysia'; ?></td>
                        <td colspan="2"><?php echo $user_v['pin_code']; ?></td>
                        <td colspan="7"><?php echo $user_v['address']; ?></td>
                        <td colspan="4"><?php echo $user_v['created_datetime']; ?></td>
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

<?php
} elseif ($module == 'subadmin') {
?>
    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th style="font-weight: bold;">Sno</th>
                <th style="font-weight: bold;">Name</th>
                <th style="font-weight: bold;">Phone</th>
                <th style="font-weight: bold;">Email</th>
            </tr>
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($export_data)) {
                $i = 0;
                foreach ($export_data as $user_k => $user_v) {
            ?>
                    <tr>
                        <td><?php echo ++$i; ?></td>
                        <td><?php echo get_enum_list('title', $user_v['title']) . ' ' . $user_v['first_name'] . ' ' . $user_v['last_name']; ?></td>
                        <td><?php echo $user_v['phone']; ?></td>
                        <td><?php echo provab_decrypt($user_v['email']); ?></td>
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

<?php
}
?>