<link rel="stylesheet" href="//cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" type="text/css" />
<style>
    #userDataCrmClose {
        margin-top: -32px;
    }
    #userDataCrmHeader {
        background-color: #8F53A1;
        color: white;
    }
    #userDataCrmPageTitle {
        margin-bottom: 20px;
    }
    th {
        text-align: center;
        vertical-align: middle;
    }
    .child_th_subheadings {
        border-right-width: 1px !important;
    }
</style>
<h2 id="userDataCrmPageTitle">CRM - <?php echo $status_text . " " . $module; ?> Users</h2>
<div class="modal fade" id="userDataCrm" tabindex="-1" role="dialog" aria-labelledby="userDataCrmLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" id="userDataCrmHeader">
                <h4 class="modal-title" id="userDataCrmLabel"></h4>
                <button type="button" data-dismiss="modal" class="close" session-expired="0" id="userDataCrmClose">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30" height="30">
                        <path d="M18 6L6 18M6 6l12 12" stroke="white" stroke-width="2" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal_body_promo_list"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<?php
// debug($users_data);die;
$th_custom = "";
if (is_active_airline_module()) {
    $th_custom .= "<th colspan='2'>Flight</th>";
}
// if (is_active_package_module()) {
$th_custom .= "<th colspan='2'>Holiday</th>";
// }
if (is_active_hotel_module()) {
    $th_custom .= "<th colspan='2'>Hotel</th>";
}
if (is_active_bus_module()) {
    $th_custom .= "<th colspan='2'>Bus</th>";
}
if (is_active_transferv1_module()) {
    $th_custom .= "<th colspan='2'>Transfer</th>";
}
if (is_active_sightseeing_module()) {
    $th_custom .= "<th colspan='2'>Sightseeing</th>";
}
?>
<table class="table table-striped table-bordered" id="user_data_crm_table" style="color: black;">
    <thead>
        <tr>
            <th rowspan="2">SN</th>
            <th rowspan="2">Name</th>
            <?php
            if ($module == 'b2b') {
            ?>
                <th rowspan="2"> Agency Id</th>
                <th rowspan="2">Agency Name</th>
            <?php
            }
            ?>
            <th rowspan="2">Email</th>
            <?php
            if ($module == 'b2c') {
            ?>
                <th rowspan="2">Creation<br />Source</th>
            <?php
            }
            ?>
            <th rowspan="2">Mobile</th>
            <?php
            if ($module == 'b2b') {
            ?>
                <th rowspan="2">PAN</th>
            <?php
            }
            ?>
            <?php
            if ($th_custom != "") {
                echo $th_custom;
            }
            if ($module == 'b2c') {
            ?>
                <th rowspan="2">Promocodes</th>
            <?php
            }
            ?>
            <th rowspan="2">Contacted</th>
            <th rowspan="2">Remarks</th>
        </tr>
        <?php
        // subheadings for th_custom
        if ($th_custom != "") {
        ?>
            <tr>
                <?php
                if (is_active_airline_module()) {
                    echo "<th>Confirmed</th><th class='child_th_subheadings'>InProgress</th>";
                }
                // if (is_active_package_module()) {
                echo "<th>Confirmed</th><th class='child_th_subheadings'>InProgress</th>";
                // }
                if (is_active_hotel_module()) {
                    echo "<th>Confirmed</th><th class='child_th_subheadings'>InProgress</th>";
                }
                if (is_active_bus_module()) {
                    echo "<th>Confirmed</th><th class='child_th_subheadings'>InProgress</th>";
                }
                if (is_active_transferv1_module()) {
                    echo "<th>Confirmed</th><th class='child_th_subheadings'>InProgress</th>";
                }
                if (is_active_sightseeing_module()) {
                    echo "<th>Confirmed</th><th class='child_th_subheadings'>InProgress</th>";
                }
                ?>
            </tr>
        <?php
        }
        ?>
    </thead>
    <tbody>
        <?php
        foreach ($users_data as $u_k => $u_v) {
            $promo_val_seprator = '_*promo*_';
            $td_custom = "";
            $promo_list[$u_k] = array();
            if (is_active_airline_module()) {
                $td_custom .= '<td>' . count($u_v["flight_booking_data"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_flight_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_CONFIRMED&status1=BOOKING_INPROGRESS' . '">' . (count($u_v["flight_booking_data"]) > 0 ? 'View' : '') . '</a></span></td>';
                $td_custom .= '<td>' . count($u_v["flight_booking_data_inprogress"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_flight_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_INPROGRESS' . '">' . (count($u_v["flight_booking_data_inprogress"]) > 0 ? 'View' : '') . '</a></span></td>';
                if ($module == 'b2c') {
                    foreach ($u_v['flight_booking_data_all'] as $f_k => $f_v) {
                        $mod = 'flight';
                        $index = 1;
                        if ($f_v['promo_code'] != "") {
                            $promo_value = $f_v['promo_code'];
                            if (count($promo_list[$u_k]) > 0) {
                                foreach ($promo_list[$u_k] as $t_k => $t_v) {
                                    $temp = explode($promo_val_seprator, $t_v);
                                    if ($promo_value == $temp[2] && $temp[1] == $mod) {
                                        $index = ++$temp[0];
                                        unset($promo_list[$u_k]);
                                    }
                                }
                            }
                            $promo_list[$u_k][] = $index . $promo_val_seprator . $mod . $promo_val_seprator . $promo_value;
                        }
                    }
                }
            }
            // if (is_active_package_module()) {
            $td_custom .= '<td>' . count($u_v["holiday_booking_data"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_holiday_report?booked_by_id=' . $u_v['user_id'] . '&status=BOOKING_CONFIRMED' . '">' . (count($u_v["holiday_booking_data"]) > 0 ? 'View' : '') . '</a></span></td>';
            $td_custom .= '<td>' . count($u_v["holiday_booking_data_inprogress"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_holiday_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_INPROGRESS' . '">' . (count($u_v["holiday_booking_data_inprogress"]) > 0 ? 'View' : '') . '</a></span></td>';
            if ($module == 'b2c') {
                foreach ($u_v['holiday_booking_data_all'] as $f_k => $f_v) {
                    $mod = 'holiday';
                    $index = 1;
                    if ($f_v['promocode'] != "") {
                        $promo_value = $f_v['promocode'];
                        if (count($promo_list[$u_k]) > 0) {
                            foreach ($promo_list[$u_k] as $t_k => $t_v) {
                                $temp = explode($promo_val_seprator, $t_v);
                                if ($promo_value == $temp[2] && $temp[1] == $mod) {
                                    $index = ++$temp[0];
                                    unset($promo_list[$u_k]);
                                }
                            }
                        }
                        $promo_list[$u_k][] = $index . $promo_val_seprator . $mod . $promo_val_seprator . $promo_value;
                    }
                }
            }
            // }
            if (is_active_hotel_module()) {
                $td_custom .= '<td>' . count($u_v["hotel_booking_data"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_hotel_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_CONFIRMED' . '">' . (count($u_v["hotel_booking_data"]) > 0 ? 'View' : '') . '</a></span></td>';
                $td_custom .= '<td>' . count($u_v["hotel_booking_data_inprogress"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_hotel_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_INPROGRESS' . '">' . (count($u_v["hotel_booking_data_inprogress"]) > 0 ? 'View' : '') . '</a></span></td>';
                if ($module == 'b2c') {
                    foreach ($u_v['hotel_booking_data_all'] as $f_k => $f_v) {
                        $mod = 'hotel';
                        $index = 1;
                        if ($f_v['promo_code'] != "") {
                            $promo_value = $f_v['promo_code'];
                            if (count($promo_list[$u_k]) > 0) {
                                foreach ($promo_list[$u_k] as $t_k => $t_v) {
                                    $temp = explode($promo_val_seprator, $t_v);
                                    if ($promo_value == $temp[2] && $temp[1] == $mod) {
                                        $index = ++$temp[0];
                                        unset($promo_list[$u_k]);
                                    }
                                }
                            }
                            $promo_list[$u_k][] = $index . $promo_val_seprator . $mod . $promo_val_seprator . $promo_value;
                        }
                    }
                }
            }
            if (is_active_bus_module()) {
                $td_custom .= '<td>' . count($u_v["bus_booking_data"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_bus_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_CONFIRMED' . '">' . (count($u_v["bus_booking_data"]) > 0 ? 'View' : '') . '</a></span></td>';
                $td_custom .= '<td>' . count($u_v["bus_booking_data_inprogress"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_bus_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_INPROGRESS' . '">' . (count($u_v["bus_booking_data_inprogress"]) > 0 ? 'View' : '') . '</a></span></td>';
                if ($module == 'b2c') {
                    foreach ($u_v['bus_booking_data_all'] as $f_k => $f_v) {
                        $mod = 'bus';
                        $index = 1;
                        if ($f_v['promo_code'] != "") {
                            $promo_value = $f_v['promo_code'];
                            if (count($promo_list[$u_k]) > 0) {
                                foreach ($promo_list[$u_k] as $t_k => $t_v) {
                                    $temp = explode($promo_val_seprator, $t_v);
                                    if ($promo_value == $temp[2] && $temp[1] == $mod) {
                                        $index = ++$temp[0];
                                        unset($promo_list[$u_k]);
                                    }
                                }
                            }
                            $promo_list[$u_k][] = $index . $promo_val_seprator . $mod . $promo_val_seprator . $promo_value;
                        }
                    }
                }
            }
            if (is_active_transferv1_module()) {
                $td_custom .= '<td>' . count($u_v["transferv1_booking_data"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_transfers_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_CONFIRMED' . '">' . (count($u_v["transferv1_booking_data"]) > 0 ? 'View' : '') . '</a></span></td>';
                $td_custom .= '<td>' . count($u_v["transferv1_booking_data_inprogress"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_transferv1_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_INPROGRESS' . '">' . (count($u_v["transferv1_booking_data_inprogress"]) > 0 ? 'View' : '') . '</a></span></td>';
                if ($module == 'b2c') {
                    foreach ($u_v['transferv1_booking_data_all'] as $f_k => $f_v) {
                        $mod = 'transferv1';
                        $index = 1;
                        if ($f_v['promo_code'] != "") {
                            $promo_value = $f_v['promo_code'];
                            if (count($promo_list[$u_k]) > 0) {
                                foreach ($promo_list[$u_k] as $t_k => $t_v) {
                                    $temp = explode($promo_val_seprator, $t_v);
                                    if ($promo_value == $temp[2] && $temp[1] == $mod) {
                                        $index = ++$temp[0];
                                        unset($promo_list[$u_k]);
                                    }
                                }
                            }
                            $promo_list[$u_k][] = $index . $promo_val_seprator . $mod . $promo_val_seprator . $promo_value;
                        }
                    }
                }
            }
            if (is_active_sightseeing_module()) {
                $td_custom .= '<td>' . count($u_v["sightseeing_booking_data"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_activities_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_CONFIRMED' . '">' . (count($u_v["sightseeing_booking_data"]) > 0 ? 'View' : '') . '</a></span></td>';
                $td_custom .= '<td>' . count($u_v["sightseeing_booking_data_inprogress"]) . '<span style="float: right;"><a href="' . base_url() . 'report/' . $module . '_activities_report?created_by_id=' . $u_v['user_id'] . '&status=BOOKING_INPROGRESS' . '">' . (count($u_v["sightseeing_booking_data_inprogress"]) > 0 ? 'View' : '') . '</a></span></td>';
                if ($module == 'b2c') {
                    foreach ($u_v['sightseeing_booking_data_all'] as $f_k => $f_v) {
                        $mod = 'sightseeing';
                        $index = 1;
                        if ($f_v['promo_code'] != "") {
                            $promo_value = $f_v['promo_code'];
                            if (count($promo_list[$u_k]) > 0) {
                                foreach ($promo_list[$u_k] as $t_k => $t_v) {
                                    $temp = explode($promo_val_seprator, $t_v);
                                    if ($promo_value == $temp[2] && $temp[1] == $mod) {
                                        $index = ++$temp[0];
                                        unset($promo_list[$u_k]);
                                    }
                                }
                            }
                            $promo_list[$u_k][] = $index . $promo_val_seprator . $mod . $promo_val_seprator . $promo_value;
                        }
                    }
                }
            }
        ?>
            <tr>
                <td></td>
                <td style="text-wrap: nowrap;"><?php echo get_enum_list('title', $u_v['title']) . ' ' . $u_v['first_name'] . ' ' . $u_v['last_name']; ?></td>
                <?php
                if ($module == 'b2b') {
                ?>
                    <td style="text-wrap: nowrap;"><?php echo provab_decrypt($u_v['uuid']); ?></td>
                    <td style="text-wrap: nowrap;"><?php echo empty($u_v['agency_name']) == false ? $u_v['agency_name'] : 'Not Added'; ?></td>
                <?php
                }
                ?>
                <td style="text-wrap: nowrap;"><?php echo provab_decrypt($u_v['email']); ?></td>
                <?php
                if ($module == 'b2c') {
                ?>
                    <td style="text-wrap: nowrap;"><?php echo $u_v['creation_source']; ?></td>
                <?php
                }
                ?>
                <td style="text-wrap: nowrap;"><?php echo $u_v['phone']; ?></td>
                <?php
                if ($module == 'b2b') {
                ?>
                    <td style="text-wrap: nowrap;">
                        <?php
                        if ($u_v['attachment'] != "") {
                            $filePath = DOMAIN_IMAGE_DIR . $u_v['attachment'];
                            $fileFullPath = $_SERVER['DOCUMENT_ROOT'] . DOMAIN_IMAGE_DIR . $u_v['attachment'];
                            if (file_exists($fileFullPath)) {
                                $fileNameExt = end(explode('.', $u_v['attachment']));
                                if (strtolower($fileNameExt) == 'pdf') {
                                    // $pdfPath = $fileFullPath;
                                    $pdfPath = $filePath;
                        ?>
                                    <iframe class="pdf-display" src="<?php echo $pdfPath; ?>" frameborder="0" width="75px" height="60px"></iframe>
                                    <p><?php echo $u_v['pan_number'] ? $u_v['pan_number'] : ''; ?>
                                        <a href="<?php echo $pdfPath; ?>" target="_blank" rel="noopener noreferrer">View</a>
                                    </p>
                                <?php
                                } else {
                                ?>
                                    <figure>
                                        <a href="<?php echo $filePath; ?>" target="_blank" rel="noopener noreferrer"><img src="<?php echo $filePath; ?>" alt="PAN image" title="PAN of the agent/agency" width="75px" height="60px" /></a>
                                        <figcaption><?php echo $u_v['pan_number'] ? $u_v['pan_number'] : ''; ?></figcaption>
                                    </figure>
                        <?php
                                }
                            } else {
                                echo "File not found.";
                            }
                        } else {
                            echo "No data.";
                        }
                        ?>
                    </td>
                <?php
                }
                if ($td_custom != "") {
                    echo $td_custom;
                }
                $attributes = json_decode($u_v['attributes'], true);
                if ($module == 'b2c') {
                    $total_promos = 0;
                    foreach ($promo_list[$u_k] as $key => $value) {
                        $value_list = explode($promo_val_seprator, $value);
                        $total_promos += $value_list[0];
                    }
                ?>
                    <td style="text-wrap: nowrap;"><?php echo $total_promos . ' (' . count($promo_list[$u_k]) . ')'; ?><span style="float: right;"><a href="#" u_id="<?php echo $u_k; ?>" class="promo_list_view"><?php echo count($promo_list[$u_k]) > 0 ? 'View' : ''; ?></a></span></td>
                <?php
                }
                ?>
                <td style="text-wrap: nowrap; min-width: 280px">
                    <input type="checkbox" id="emailSentCheckbox_<?php echo $u_v['user_id']; ?>" onchange="updateData(event, this.id, <?php echo $u_v['user_id']; ?>)" />
                    <label for="emailSentCheckbox_<?php echo $u_v['user_id']; ?>">Email&nbsp;&nbsp;<span id="count_emailSentCheckbox_<?php echo $u_v['user_id']; ?>" style="float: right;">(<?php echo $attributes['emailUpdateCount'] > 0 ? $attributes['emailUpdateCount'] : 0; ?>)</span></label>
                    <input type="checkbox" id="calledCheckbox_<?php echo $u_v['user_id']; ?>" onchange="updateData(event, this.id, <?php echo $u_v['user_id']; ?>)" />
                    <label for="calledCheckbox_<?php echo $u_v['user_id']; ?>">Call&nbsp;&nbsp;<span id="count_calledCheckbox_<?php echo $u_v['user_id']; ?>" style="float: right;">(<?php echo $attributes['callUpdateCount'] > 0 ? $attributes['callUpdateCount'] : 0; ?>)</span></label>
                    <input type="checkbox" id="visitedCheckbox_<?php echo $u_v['user_id']; ?>" onchange="updateData(event, this.id, <?php echo $u_v['user_id']; ?>)" />
                    <label for="visitedCheckbox_<?php echo $u_v['user_id']; ?>">Visit&nbsp;&nbsp;<span id="count_visitedCheckbox_<?php echo $u_v['user_id']; ?>" style="float: right;">(<?php echo $attributes['visitUpdateCount'] > 0 ? $attributes['visitUpdateCount'] : 0; ?>)</span></label>
                </td>
                <td style="min-width: 300px;">
                    <div>
                        <span id="remarksDisplay_<?php echo $u_v['user_id']; ?>"><?php echo $attributes['remarks'] ? nl2br($attributes['remarks'], true) : '-click to add-'; ?></span>
                        <div id="remarksDisplayDiv_<?php echo $u_v['user_id']; ?>">
                            <a href="#" onclick="toggleRemarksEdit(event, <?php echo $u_v['user_id']; ?>)">Edit</a>
                        </div>
                    </div>
                    <div id="remarksEdit_<?php echo $u_v['user_id']; ?>" style="display:none;">
                        <textarea id="remarksInput_<?php echo $u_v['user_id']; ?>" rows="2" cols="50"><?php echo $attributes['remarks']; ?></textarea>
                        <a href="#" onclick="cancelEdit(event, <?php echo $u_v['user_id']; ?>)">Cancel</a> |
                        <a href="#" onclick="updateData(event, 'remarksInput_<?php echo $u_v['user_id']; ?>', <?php echo $u_v['user_id']; ?>)">Save</a>
                    </div>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<input type="hidden" id="module" value="<?php echo htmlspecialchars(json_encode($module)); ?>" readonly />
<?php
if ($module == 'b2c') {
?>
    <input type="hidden" id="promo_list" value="<?php echo htmlspecialchars(json_encode($promo_list)); ?>" readonly />
<?php
}
?>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<!-- Responsive extension -->
<script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script>
<!-- Buttons extension -->
<script src="//cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#user_data_crm_table').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "scrollY": false,
            "scrollX": true,
            // "scrollCollapse": true,
            // "pageLength": 407,
            sPaginationType: "full_numbers",
            // bJQueryUI: true,
            "fnDrawCallback": function(oSettings) {
                /* Need to redo the counters if filtered or sorted */
                if (oSettings.bSorted || oSettings.bFiltered) {
                    for (let i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++) {
                        $('td:eq(0)', oSettings.aoData[oSettings.aiDisplay[i]].nTr).html(i + 1);
                    }
                }
            },
            // "aoColumnDefs": [{
            //     "bSortable": false,
            //     "aTargets": [0]
            // }],
            // "aaSorting": [[ 1, 'asc' ]]
        });
    });
    let module = JSON.parse(document.getElementById('module').value);
    $(document).on('click', '.promo_list_view', function(e) {
        let promo_list = JSON.parse(document.getElementById('promo_list').value);
        e.preventDefault();
        let u_id = $(this).attr('u_id');
        let u_promo_list = promo_list[u_id];
        let table_promo = "<table class='table table-bordered table-responsive'>";
        if (u_promo_list.length > 0) {
            table_promo += "<thead><tr><th>SN</th><th>Module</th><th>Code</th><th>Frequency</th></tr></thead>";
            table_promo += "<tbody>";
            let i = 0;
            $.each(u_promo_list, function(index, value) {
                let p_value = value.split("_*promo*_");
                table_promo += "<tr>";
                table_promo += `<td>${++i}</td>`;
                table_promo += `<td>${p_value[1]}</td>`;
                table_promo += `<td>${p_value[2]}</td>`;
                table_promo += `<td>${p_value[0]}</td>`;
                table_promo += "</tr>";
            });
            table_promo += "</tbody>";
        } else {
            table_promo += `No promocodes used so far`;
        }
        table_promo += "</table>";
        $('#userDataCrmLabel').html("");
        $('#userDataCrmLabel').html("Promo codes used");
        $('#modal_body_promo_list').html("");
        $('#modal_body_promo_list').html(table_promo);
        $('#userDataCrm').modal('show');
    });
    // Function to update data (email, call, remarks)
    function updateData(event, elementId, user_id) {
        event.preventDefault();
        // toastr.info('Please Wait!!!');
        let data = {
            user_id: user_id
        };
        let value;
        if (elementId === "emailSentCheckbox_" + user_id || elementId === "calledCheckbox_" + user_id || elementId === "visitedCheckbox_" + user_id) {
            value = document.getElementById(elementId).checked;
        } else {
            value = document.getElementById(elementId).value;
        }
        data[elementId] = value;
        $.ajax({
            url: app_base_url + "user/users_crm_remarks_update",
            type: "POST",
            data: data,
            success: function(response) {
                response = JSON.parse(response);
                if (data["remarksInput_" + user_id] !== undefined) {
                    // Update the remarks display
                    document.getElementById("remarksDisplay_" + user_id).innerHTML = value.replace(/\n/g, "<br />");
                    document.getElementById("remarksInput_" + user_id).value = value;
                }
                if (response.elementId === "emailSentCheckbox_" + user_id || response.elementId === "calledCheckbox_" + user_id || response.elementId === "visitedCheckbox_" + user_id) {
                    let countTextElement = document.getElementById("count_" + response.elementId)
                    let countValText = countTextElement.innerText;
                    let countVal = parseInt(countValText.match(/\((\d+)\)/)[1]);;
                    // let countVal = parseInt((countValText.split('(')[1]).split(')')[0]);
                    countVal++;
                    countValText = countValText.replace(/\(\d+\)/, "(" + countVal + ")");
                    countTextElement.innerText = countValText.replace(/\(\d+\)/, "(" + countVal + ")");
                    setTimeout(function() {
                        document.getElementById(response.elementId).checked = false;
                    }, 1000);
                }
                toastr.info(response.message);
            },
            error: function(xhr, status, error) {
                toastr.info(xhr.responseText);
            }
        });
        // Hide edit section
        cancelEdit(event, user_id);
    }
    // Function to toggle edit mode for remarks
    function toggleRemarksEdit(event, user_id) {
        event.preventDefault();
        let editDiv = document.getElementById("remarksEdit_" + user_id);
        let displaySpan = document.getElementById("remarksDisplay_" + user_id);
        let editBtn = document.getElementById("remarksDisplayDiv_" + user_id).querySelector("a");
        let inputField = document.getElementById("remarksInput_" + user_id);
        // Toggle visibility of elements
        editDiv.style.display = "block";
        displaySpan.style.display = "none";
        editBtn.style.display = "none";
    }
    // Function to cancel editing remarks
    function cancelEdit(event, user_id) {
        event.preventDefault();
        let editDiv = document.getElementById("remarksEdit_" + user_id);
        let displaySpan = document.getElementById("remarksDisplay_" + user_id);
        let inputField = document.getElementById("remarksInput_" + user_id);
        let editBtn = document.getElementById("remarksDisplayDiv_" + user_id).querySelector("a");
        // Hide edit section
        editDiv.style.display = "none";
        displaySpan.style.display = "block";
        editBtn.style.display = "block";
    }
</script>