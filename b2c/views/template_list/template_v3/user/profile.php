<?php

$get_month_names = array(

    1 => 'Jan',

    2 => 'Feb',

    3 => 'Mar',

    4 => 'Apr',

    5 => 'May',

    6 => 'Jun',

    7 => 'Jul',

    8 => 'Aug',

    9 => 'Sep',

    10 => 'Oct',

    11 => 'Nov',

    12 => 'Dec'

);

?>
<style>
    .hasDatepicker {
        z-index: 999 !important;
    }

    .tab-pane tr td {
        text-align: center !important;
    }

    .userfstep {
        overflow: visible;
    }

    .referandearn a.addbutton {
        float: none;
        border: none;
        height: 50px;
        padding: 16px 20px;
        border-radius: 10px;
    }

    input#refercode {
        min-width: 380px;
        height: 50px;
        border: 1px solid #7c7c7c;
        border-radius: 7px;
        color: #acacac;
        padding: 0 10px;
        font-size: 15px;
    }
</style>
<div class="content-wrapper dashboard_section">

    <div class="container">

        <div class="staffareadash">

            <?php echo $GLOBALS['CI']->template->isolated_view('share/profile_navigator_tab') ?>

            <div class="tab-content sidewise_tab">

                <div role="tabpanel" class="tab-pane <?php echo ((isset($_GET['active']) == false || @$_GET['active'] == 'dashboard')) ? 'active' : '' ?>" id="dashbrd">

                    <div class="trvlwrap">

                        <div class="seperate_shadow">

                            <h3 class="welcmnotespl" style="margin-left: 0px!important">Hi,

                                <?= $full_name ?>

                            </h3>

                            <div class="smlwel" style="margin-left: 0px!important">All your trips booked with us will
                                appear here and you'll be able to manage everything!</div>

                            <div class="bokinstts">

                                <div class="col-xs-3 nopad">

                                    <div class="insidebx color1">

                                        <div class="ritlstxt">

                                            <div class="contbokd">

                                                <?= $booking_counts['flight_booking_count'] ?>

                                            </div>Flights booked
                                        </div>

                                        <span class="witbook fa fa-plane"></span> <a href="<?= base_url() . 'index.php/report/flights?default_view=' . META_AIRLINE_COURSE ?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a>
                                    </div>

                                </div>

                                <div class="col-xs-3 nopad">

                                    <div class="insidebx color2">

                                        <div class="ritlstxt">

                                            <div class="contbokd">

                                                <?= $booking_counts['hotel_booking_count'] ?>

                                            </div>Hotel booked
                                        </div>

                                        <span class="witbook fa fa-bed"></span> <a href="<?= base_url() . 'index.php/report/hotels?default_view=' . META_AIRLINE_COURSE ?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a>
                                    </div>

                                </div>

                                <div class="col-xs-3 nopad">

                                    <div class="insidebx color5">

                                        <div class="ritlstxt">

                                            <div class="contbokd">

                                                <?= $booking_counts['holiday_booking_count'] ?>

                                            </div>Holiday booked
                                        </div>

                                        <span class="witbook fa fa-suitcase"></span> <a href="<?= base_url() . 'index.php/report/holidays?default_view=' . META_AIRLINE_COURSE ?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a>
                                    </div>

                                </div>


                                <?php if (is_active_bus_module()) : ?>
                                    <div class="col-xs-3 nopad">

                                        <div class="insidebx color3">

                                            <div class="ritlstxt">

                                                <div class="contbokd">

                                                    <?= $booking_counts['bus_booking_count'] ?>

                                                </div>Buses booked
                                            </div>

                                            <span class="witbook fa fa-bus"></span> <a href="<?= base_url() . 'index.php/report/buses?default_view=' . META_BUS_COURSE ?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a>
                                        </div>

                                    </div>
                                <?php endif; ?>

                                <?php if (is_active_transferv1_module()) : ?>

                                    <div class="col-xs-3 nopad">

                                        <div class="insidebx color4">

                                            <div class="ritlstxt">

                                                <div class="contbokd">

                                                    <?= $booking_counts['transfer_booking_count'] ?>

                                                </div>Transfers booked
                                            </div>

                                            <span class="witbook fa fa-taxi"></span> <a href="<?= base_url() . 'index.php/report/transfers?default_view=' . META_TRANSFERV1_COURSE ?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a>
                                        </div>

                                    </div>

                                <?php endif; ?>

                                <?php if (is_active_sightseeing_module()) : ?>

                                    <div class="col-xs-3 nopad">

                                        <div class="insidebx color5">

                                            <div class="ritlstxt">

                                                <div class="contbokd">

                                                    <?= $booking_counts['sightseeing_booking_count'] ?>

                                                </div>Activity booked
                                            </div>

                                            <span class="witbook fa fa-binoculars"></span> <a href="<?= base_url() . 'index.php/report/activities?default_view=' . META_SIGHTSEEING_COURSE ?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a>
                                        </div>

                                    </div>

                                <?php endif; ?>

                                <?php


                                // debug(is_active_car_module());
                                // debug("vb");
                                if (is_active_car_module()) { ?>

                                    <!--<div class="col-xs-3 nopad">

                <div class="insidebx color6">

                <div class="ritlstxt">

                    <div class="contbokd">

                        <?= $booking_counts['car_booking_count'] ?>

                    </div>Car booked </div>

                <span class="witbook fa fa-taxi"></span> <a href="<?= base_url() . 'index.php/report/car?default_view=' . META_CAR_COURSE ?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a> </div>

                </div> 
<div class="col-xs-3 nopad">

                <div class="insidebx color6">

                <div class="ritlstxt">

                    <div class="contbokd">

                        <?= $booking_counts['private_car_booking_count'] ?>

                    </div>Private Car booked </div>

                <span class="witbook fa fa-car"></span> <a href="<?= base_url() . 'index.php/report/privatecar?default_view=' . META_CAR_COURSE ?>" class="htview"> View detail <span class="far fa-arrow-right"></span> </a> </div>

                </div>-->
                                <?php } ?>

                            </div>

                        </div>

                        <div class="clearfix"></div>

                        <div class="retnset">

                            <div class="col-xs-6 nopad full_nty">

                                <div class="pading_spl">

                                    <div class="spl_box">

                                        <h4 class="dskrty">Recent Activities</h4>

                                        <div class="backfully">

                                            <?php
                                            // debug($latest_transaction);exit;
                                            if (valid_array($latest_transaction) == true) {

                                                foreach ($latest_transaction as $lt_k => $lt_v) {

                                                    switch ($lt_v['transaction_type']) {

                                                        case 'flight':

                                                            $icon = 'plane';

                                                            $boking_source = PROVAB_FLIGHT_BOOKING_SOURCE;

                                                            break;

                                                        case 'hotel':

                                                            $icon = 'bed';

                                                            $boking_source = PROVAB_HOTEL_BOOKING_SOURCE;

                                                            break;

                                                        case 'bus':

                                                            $icon = 'bus';

                                                            $boking_source = PROVAB_BUS_BOOKING_SOURCE;

                                                            break;



                                                        case 'sightseeing':

                                                            $icon = 'binoculars';

                                                            $boking_source = PROVAB_SIGHTSEEN_BOOKING_SOURCE;

                                                            break;



                                                        case 'transferv1':

                                                            $icon = 'taxi';

                                                            $boking_source = PROVAB_TRANSFERV1_BOOKING_SOURCE;

                                                            break;



                                                        case 'car':

                                                            $icon = 'car';

                                                            $boking_source = PROVAB_CAR_BOOKING_SOURCE;

                                                            break;
                                                        case 'holiday':

                                                            $icon = 'suitcase';

                                                            $boking_source = HOLIDAY_BOOKING_SOURCE;

                                                            break;
                                                    }



                                            ?>

                                                    <a target="_blank" href="<?= base_url(); ?>index.php/voucher/<?= $lt_v['transaction_type'] ?>/<?= $lt_v['app_reference'] ?>/<?= $boking_source ?>">

                                                        <div class="rownotice2">

                                                            <div class="col-xs-2 nopad5">

                                                                <div class="lofa2 fa fa-<?= $icon ?>"></div>

                                                            </div>

                                                            <div class="col-xs-7 nopad5">

                                                                <div class="noticemsg2">

                                                                    <?= $lt_v['app_reference'] ?>

                                                                    <strong>

                                                                        <?= app_friendly_absolute_date($lt_v['created_datetime']) ?>

                                                                    </strong>
                                                                </div>

                                                            </div>

                                                            <div class="col-xs-3 nopad5"> <span class="yrtogo2">

                                                                    <?= $currency_obj->get_currency_symbol($lt_v['currency']) ?>

                                                                    <?= $lt_v['grand_total'] ?>

                                                                </span> </div>

                                                        </div>

                                                    </a>

                                                <?php }
                                            } else { ?>

                                                <div class="col-md-12">

                                                    <center>

                                                        No Activities Found

                                                    </center>

                                                </div>

                                            <?php } ?>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="clearfix"></div>

                    </div>

                </div>
                <!---Rewards------------->
                <div role="tabpanel" class="tab-pane <?php echo (@$_GET['active'] == 'rewards') ? 'active' : '' ?>" id="rewardsinfo">
                    <div class="dashdiv">
                        <div class="alldasbord">
                            <div class="userfstep">

                                <a class="addbutton" href="<?php echo base_url() ?>user/product/" target="_blank">Product Page</a>
                                <div class="col-md-12 ">

                                    <?php
                                    $pending = array_column($reward_booking_report, 'pending_rewardpoint');
                                    ?>
                                    <div class="col-md-12 nopad">
                                        <div class="top_boxes">
                                            <ul class="top_box_ul">
                                                <li>Total Used
                                                    Rewards :
                                                    <strong><?php echo $reward_total_report[0]['used_reward']; ?></strong>
                                                </li><br />
                                                <!-- <li>Available Rewards<br><?php echo end($pending); ?></li> -->
                                                <li>Available Rewards :
                                                    <strong><?php echo $user['pending_reward']; ?></strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-12 nopad">
                                        <ul class="nav nav-tabs tabssyb">
                                            <li class="active"><a data-toggle="tab" href="#menu1">Booking Details</a>
                                            </li>
                                            <li class="hide"><a data-toggle="tab" href="#menu1">Spend/Redeem
                                        </ul>
                                        <!-- changes removed classes fade in active for rewards section -->
                                        <div id="menu1" class="tab-pane">
                                            <div class="col-md-12 nopad">
                                                <?php if ($reward_booking_report) { ?>
                                                    <!-- changes added id rewards_info_table -->
                                                    <table id="rewards_info_table" class="table table-bordered" style="width: 100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl.No</th>
                                                                <th>Module</th>
                                                                <th>Appreference</th>
                                                                <!-- commented these headings for styles reward section and commented respective tds as well -->
                                                                <!-- <th>Previous</th> -->
                                                                <th style="color:red">Spend</th>
                                                                <th style="color:green">Earned Rewards</th>
                                                                <th>Date</th>
                                                                <!-- <th>Redeem points</th> -->

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $i = 1;
                                                            $total_used = 0;
                                                            $pending = array_column($reward_booking_report, 'pending_rewardpoint');
                                                            // debug($reward_booking_report_data);
                                                            foreach ($reward_booking_report_data as $key => $value) { ?>
                                                                <tr>
                                                                    <td><?= $i; ?></td>
                                                                    <td><?= $value['module'] ?></td>
                                                                    <td><?= $value['book_id'] ?></td>
                                                                    <!-- <td><?= ($value['pending_rewardpoint'] + $value['used_rewardpoint']) - $value['reward_earned'] ?>
                                                            </td> -->
                                                                    <td style="color:red"><?= $value['used_rewardpoint'] ?></td>
                                                                    <td style="color:green"><?= $value['reward_earned'] ?></td>
                                                                    <td><?= $value['created'] ?></td>
                                                                    <!-- <td><?= $value['pending_rewardpoint'] ?></td> -->
                                                                    <!-- <td> -->
                                                                    <?php
                                                                    $function = "";
                                                                    if ($value['module'] == "Flight") {
                                                                        $function = "flight";
                                                                    } else if ($value['module'] == "Hotel") {
                                                                        $function = "hotel";
                                                                    } else if ($value['module'] == "Car") {
                                                                        $function = "car";
                                                                    } else if ($value['module'] == "Activities") {
                                                                        $function = "activity_crs";
                                                                    } else if ($value['module'] == "Transfers") {
                                                                        $function = "transfer_crs";
                                                                    } else if ($value['module'] == "Holiday" && $value['module'] == "") {
                                                                        $function = "voucher";
                                                                    }
                                                                    ?>

                                                                    <!-- </td> -->
                                                                    <?php
                                                                    $total_used += $value['used_rewardpoint'];
                                                                    ?>
                                                                </tr>
                                                            <?php $i++;
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>

                                                <?php } else { ?>

                                                    <div class="alert alert-info">
                                                        <strong>Booking not found</strong>
                                                    </div>

                                                <?php     } ?>
                                            </div>
                                        </div>



                                        <div class="tab-content" style="padding-top: 20px;display: none">



                                            <div id="home" class="tab-pane fade in active">

                                                <div class="col-md-12 nopad">
                                                    <?php if ($reward_booking_report) { ?>
                                                        <table class="table table-bordered" style="width: 100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Booking No</th>
                                                                    <th>Pending Rewards</th>
                                                                    <th>Earned Rewards</th>
                                                                    <th>Used rewards</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                $total_used = 0;
                                                                $pending = array_column($reward_booking_report, 'pending_rewardpoint');
                                                                foreach ($reward_booking_report as $key => $value) {
                                                                    // debug($value);
                                                                ?>
                                                                    <tr>
                                                                        <td><?= $i ?></td>
                                                                        <td><?= $value['pending_rewardpoint'] ?></td>
                                                                        <td><?= $value['reward_earned'] ?></td>
                                                                        <td><?= $value['used_rewardpoint'] ?></td>
                                                                        <?php
                                                                        $total_used += $value['used_rewardpoint'];

                                                                        ?>
                                                                    </tr>
                                                                <?php $i++;
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td colspan="3"><span class="tot">Total Used
                                                                            Rewards</span></td>
                                                                    <td><span class="tot"><?= $total_used ?></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3"><span class="tot">Pending Rewards</span>
                                                                    </td>
                                                                    <td><span class="tot"><?= end($pending) ?></span></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>

                                                    <?php } else { ?>

                                                        <div class="alert alert-info">
                                                            <strong>Booking not found</strong>
                                                        </div>

                                                    <?php     } ?>
                                                </div>
                                            </div>
                                        </div>


                                        <br />
                                        <div id="redeem-data">
                                            <div class="col-md-12 nopad">

                                                <!-- changes added heading for the table -->
                                                <h3>Products redeemed</h3>
                                                <!-- changes added table id getproduct_table -->
                                                <table class="table table-bordered" id="getproduct_table" style="width: 100%;margin-top:10px;margin-bottom: 30px;">
                                                    <thead>
                                                        <tr>

                                                            <th>Product Name</th>
                                                            <th>Used Rewards</th>
                                                            <th>Redeemed Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        //  debug($getproduct);
                                                        for ($i = 0; $i < count($getproduct); $i++) {
                                                            //    echo $i;
                                                        ?>
                                                            <tr>

                                                                <td><?php echo $getproduct[$i]['name']; ?></td>
                                                                <td><?php echo $getproduct[$i]['point']; ?></td>
                                                                <td><?php echo $getproduct[$i]['created_date']; ?></td>

                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>


                                                    </tbody>
                                                </table>






                                            </div>
                                        </div><br />


                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!---end---->

                <div role="tabpanel" class="tab-pane <?php echo (@$_GET['active'] == 'profile') ? 'active' : '' ?>" id="profile">

                    <div class="dashdiv">

                        <div class="alldasbord">

                            <div class="userfstep">

                                <div class="step_head">

                                    <h3 class="welcmnote">Hi,

                                        <?= $full_name ?>

                                    </h3>

                                    <a href="#edit_user_profile" data-aria-controls="home" data-role="tab" data-toggle="tab" class="editpro">Edit profile</a>
                                </div>

                                <div class="clearfix"></div>

                                <!-- Edit User Profile starts-->

                                <div class="tab-content">

                                    <div role="tabpanel filldiv" class="tab-pane active" id="show_user_profile">

                                        <div class="colusrdash"> <img src="<?= (empty($GLOBALS['CI']->entity_image) == false ? $GLOBALS['CI']->template->domain_images($profile_image) : $GLOBALS['CI']->template->template_images('user.png')) ?>" alt="profile Image" /> </div>

                                        <div class="useralldets">

                                            <h4 class="dashuser">

                                                <?= $full_name ?>

                                            </h4>

                                            <div class="rowother"> <span class="far fa-envelope"></span> <span class="labrti">

                                                    <?= (empty($email) == true ? '---' : $email) ?>

                                                </span> </div>

                                            <div class="rowother"> <span class="far fa-phone"></span> <span class="labrti">

                                                    <?= (($phone == 0 || $phone == '') ? '---' : $user_country_code . ' ' . $phone) ?>

                                                </span> </div>

                                            <div class="rowother"> <span class="far fa-map-marker"></span> <span class="labrti">

                                                    <?= (empty($address) == true ? '---' : $address) ?>

                                                </span> </div>

                                        </div>

                                    </div>

                                    <div role="tabpanel" class="tab-pane" id="edit_user_profile">

                                        <form action="<?= base_url() . 'index.php/user/profile?active=profile' ?>" method="post" name="edit_user_form" id="edit_user_form" enctype="multipart/form-data" autocomplete="off">

                                            <div class="infowone">

                                                <div class="clearfix"></div>

                                                <div class="paspertorgn2 paspertedit">

                                                    <div class="col-xs-3 margpas nopad">

                                                        <div class="tnlepasport">

                                                            <div class="paspolbl cellpas">Title <span class="text-danger">*</span></div>

                                                            <div class="lablmain cellpas">

                                                                <select name="title" class="clainput" required="required">

                                                                    <?= generate_options(get_enum_list('title'), (array)$title) ?>

                                                                </select>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-xs-4 margpas nopad">

                                                        <div class="tnlepasport">

                                                            <div class="paspolbl cellpas">First Name <span class="text-danger">*</span></div>

                                                            <div class="lablmain cellpas">

                                                                <input type="text" name="first_name" placeholder="first name" value="<?= $first_name ?>" class="clainput alpha remove_space no_copy_paste" maxlength="20" required />

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-xs-5 margpas nopad">

                                                        <div class="tnlepasport">

                                                            <div class="paspolbl cellpas">Last Name <span class="text-danger">*</span></div>

                                                            <div class="lablmain cellpas">

                                                                <input type="text" name="last_name" placeholder="last name" value="<?= $last_name ?>" class="clainput alpha no_space no_copy_paste" maxlength="20" required="required" />

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-xs-3 margpas nopad">

                                                        <div class="tnlepasport">

                                                            <div class="paspolbl cellpas">Country Code<span class="text-danger">*</span></div>

                                                            <div class="lablmain cellpas">

                                                                <select name="country_code" class="clainput" required="required">

                                                                    <option value='<?= $user_country_code ?>'>
                                                                        <?= $user_country_code ?></option>

                                                                    <?= generate_options($phone_code_array, (array)$user_country_code) ?>

                                                                </select>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-xs-4 margpas nopad">

                                                        <div class="tnlepasport">

                                                            <div class="paspolbl cellpas">Mobile Number <span class="text-danger">*</span></div>

                                                            <div class="lablmain cellpas">

                                                                <input type="text" name="phone" placeholder="mobile number" value="<?= (($phone == 0 || $phone == '') ? '' : $phone) ?>" class="clainput numeric" required="required" / maxlength="10" minlength="7">

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-xs-5 margpas nopad">

                                                        <div class="tnlepasport">

                                                            <div class="paspolbl cellpas">Address <span class="text-danger">*</span></div>

                                                            <div class="lablmain cellpas">

                                                                <textarea name="address" placeholder="address" class="clainput remove_space" required="required"><?= $address ?></textarea>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-xs-5 margpas nopad">

                                                        <div class="tnlepasport">

                                                            <div class="paspolbl cellpas">Profile Image</div>

                                                            <div class="lablmain cellpas">

                                                                <input type="file" name="image" accept="image/*" />

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="clearfix"></div>

                                                    <button type="submit" class="savepspot">Update</button>

                                                    <a href="#show_user_profile" data-aria-controls="home" data-role="tab" data-toggle="tab" class="cancelll">Cancel</a>
                                                </div>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                                <!-- Edit User Profile Ends-->

                            </div>

                            <div class="clearfix"></div>

                        </div>

                    </div>

                </div>


                <div role="tabpanel" class="tab-pane <?php echo (@$_GET['active'] == 'wallets') ? 'active' : '' ?>" id="wallets">
                    <div class="trvlwrap">




                        <!-- rewardsbuy start -->
                        <div class="alldasbord">
                            <div class="step_head">
                                <h3 class="dashhed">Rewards Wallet</h3>
                            </div>
                            <div class="step_head_inner">
                                <h5 class="dashhed">Reward points Used: </h5>
                                <p class="dashhed_p">
                                    <strong><?php echo $reward_total_report[0]['used_reward']; ?></strong>
                                </p>

                            </div>
                            <div class="step_head_inner">
                                <h5 class="dashhed">Reward points Available: </h5>
                                <p class="dashhed_p"><strong><?php echo $user['pending_reward']; ?></strong></p>

                            </div>

                            <div class="step_head" style="border:none">
                                <h4 class="float-left">Buy Reward Points</h4>
                                <a class="addbutton" data-toggle="modal" data-target="#add_rewardsbuy_tab">Buy Now</a>
                            </div>
                            <!-- rewardsbuy  -->
                            <div class="modal fade" id="add_rewardsbuy_tab" data-aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Buy Reward Points</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="othinformtn">
                                                <div class="tab-content">
                                                    <div class="tab-pane active" role="tabpanel">
                                                        <div class="infowone">
                                                            <form action="<?= base_url() . 'index.php/user/buyrewards' ?>" method="post" name="add_traveller_form" id="add_traveller_form" autocomplete="off">
                                                                <div class="paspertedit">
                                                                    <div class="col-md-6 col-xs-12 margpas">
                                                                        <div class="tnlepasport reward_elements">
                                                                            <div class="paspolbl cellpas">Select
                                                                                Points<span class="text-dange">*</span>
                                                                            </div>
                                                                            <div class="lablmain cellpas">
                                                                                <select name="rewardpoints" class="rewards_points clainput alpha  rewards_points_chg no_copy_paste" id="rewardpoints_points">
                                                                                    <option>select</option>
                                                                                    <?php
                                                                                    for ($i = 0; $i < count($wallet_settings); $i++) {
                                                                                    ?>
                                                                                        <option data-id="<?php echo $wallet_settings[$i]['price'] ?>" value="<?php echo $wallet_settings[$i]['reward-points'] ?>">
                                                                                            <?php echo $wallet_settings[$i]['reward-points'];  ?>
                                                                                        </option>


                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>

                                                                                <input name="rewardpoints_amount" type="text" class="clainput alpha ramount no_copy_paste" placeholder="Amount" maxlength="20" required="required" id="rewardpoints_amount" readonly>
                                                                                <span id="rewardpoints_amount_error" style="color:red;"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                    <button type="submit" id="add_rewardsbuy_btn" class="savepspot">Buy Now</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- rewardsbuy  -->
                        </div>
                        <!-- rewardsbuy  end -->
                        <table class="table table-striped table-bordered" id="wallet_report">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Rewards</th>
                                    <th>Price</th>
                                    <th>Payment status</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < count($wallet_report); $i++) {
                                ?>
                                    <tr>
                                        <td><?php echo $wallet_report[$i]['transactionid']  ?></td>
                                        <td><?php echo $wallet_report[$i]['earned_rewards']  ?></td>
                                        <td><?php echo $wallet_report[$i]['amount']  ?></td>
                                        <td><?php echo $wallet_report[$i]['paymentstatus']  ?></td>
                                        <td><?php echo $wallet_report[$i]['created_at']  ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane <?php echo (@$_GET['active'] == 'referral') ? 'active' : '' ?>" id="referral">
                    <div class="trvlwrap">
                        <div class="col-md-12">
                            <div class="referandearn">
                                <h3>Refer and Earn Now:</h3>
                                <span><input type="text" id="refercode" name="refercode" value="https://travelfreetravels.com/register?referral=<?php echo  $referral_code; ?>" readonly></span>
                                <a class="addbutton" onclick="callCopy()" target="_blank">Copy link</a>
                            </div>
                        </div>




                        <!-- rewardsbuy start -->
                        <div class="alldasbord">
                            <div class="step_head">
                                <h3 class="dashhed">Referral Report</h3>
                            </div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Referral code</th>
                                        <th>Referrer email</th>
                                        <th>user email</th>
                                        <th>Reward</th>
                                        <th>status</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($usersreferral_data); $i++) {
                                        //debug($usersreferral_data);die;
                                    ?>
                                        <tr>

                                            <td><?php echo $usersreferral_data[$i]['ref_code']; ?></td>
                                            <td><?php echo $usersreferral_data[$i]['ref_email']; ?></td>
                                            <td><?php echo $usersreferral_data[$i]['user_email']; ?></td>
                                            <td><?php echo $usersreferral_data[$i]['comm_amount']; ?></td>
                                            <td><?php echo $usersreferral_data[$i]['status']; ?></td>
                                            <td><?php echo $usersreferral_data[$i]['comm_date']; ?></td>


                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <div data-role="tabpanel" class="tab-pane <?php echo (@$_GET['active'] == 'traveller') ? 'active' : '' ?>" id="travellerinfo">

                    <div class="trvlwrap">

                        <div class="alldasbord">

                            <div class="step_head">

                                <h3 class="welcmnote">Travellers Details</h3>

                                <a class="addbutton" data-toggle="modal" data-target="#add_traveller_tab">Add
                                    Traveller</a>
                            </div>

                            <!-- Add traveller Modal Starts-->

                            <div class="modal fade" id="add_traveller_tab" data-aria-labelledby="myModalLabel">

                                <div class="modal-dialog modal-lg" role="document">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                            <h4 class="modal-title" id="myModalLabel">Add Traveller</h4>

                                        </div>

                                        <div class="modal-body">

                                            <div class="othinformtn">

                                                <div class="tab-content">

                                                    <div class="tab-pane active" role="tabpanel">

                                                        <div class="infowone">

                                                            <form action="<?= base_url() . 'index.php/user/add_traveller?active=traveller' ?>" method="post" name="add_traveller_form" id="add_traveller_form" autocomplete="off">

                                                                <div class="paspertedit">

                                                                    <div class="col-xs-4 margpas">

                                                                        <div class="tnlepasport">

                                                                            <div class="paspolbl cellpas">First
                                                                                Name<span class="text-dange">*</span>
                                                                            </div>

                                                                            <div class="lablmain cellpas">

                                                                                <input name="traveller_first_name" type="text" class="clainput alpha no_copy_paste" placeholder="Enter First Name" maxlength="20" required="required" id="traveller_first_name">
                                                                                <span id="traveller_first_name_error" style="color:red;"></span>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                    <div class="col-xs-4 margpas">

                                                                        <div class="tnlepasport">

                                                                            <div class="paspolbl cellpas">Last Name<span class="text-dange">*</span></div>

                                                                            <div class="lablmain cellpas">

                                                                                <input name="traveller_last_name" type="text" class="clainput alpha no_space no_copy_paste" placeholder="Enter Last Name" maxlength="20" required="required" id="traveller_last_name">
                                                                                <span id="traveller_last_name_error" style="color:red;"></span>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                    <div class="col-xs-3 margpas">

                                                                        <div class="tnlepasport">

                                                                            <div class="paspolbl cellpas">DOB<span class="text-dange">*</span></div>

                                                                            <div class="lablmain cellpas">

                                                                                <input name="traveller_date_of_birth" id="add-travel-date-picker" type="text" class="disable-date-auto-update auto-datepicker clainput add_traveller_dob" placeholder="DOB" readonly required="required">
                                                                                <span id="add-travel-date-picker_error" style="color:red;"></span>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                    <div class="col-xs-4 margpas">

                                                                        <div class="tnlepasport">

                                                                            <div class="paspolbl cellpas">Email <span class="text-dange">*</span></div>

                                                                            <div class="lablmain cellpas">

                                                                                <input name="traveller_email" type="text" class="clainput validate_email" placeholder="Please Enter Email ID" maxlength="40" required="required" id="traveller_email">
                                                                                <span id="traveller_email_error" style="color:red;"></span>

                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                    <div class="clearfix"></div>

                                                                    <button type="submit" id="add_traveller_btn" class="savepspot">Add</button>

                                                                    <a class="cancelll" data-dismiss="modal">Cancel</a>
                                                                </div>

                                                            </form>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- Add traveller Modal Ends-->

                            <div class="fulltable">

                                <div class="trow tblhd">

                                    <div class="col-xs-3 tblpad"> <span class="lavltr">Name</span> </div>

                                    <div class="col-xs-2 tblpad"> <span class="lavltr">DOB</span> </div>

                                    <div class="col-xs-3 tblpad"> <span class="lavltr">Email</span> </div>

                                    <div class="col-xs-2 tblpad"> <span class="lavltr textalgn_rit">Action</span> </div>

                                </div>

                                <?php

                                if (valid_array($traveller_details)) {

                                    $cutoff = date('Y', strtotime('+20 years'));

                                    //current year

                                    $now = date('Y');

                                    foreach ($traveller_details as $traveller_k => $traveller_v) {

                                        extract($traveller_v);

                                        $passport_issuing_country_options = generate_options($country_list);

                                        $passport_day_options    = generate_options(get_day_numbers(), (array)$passport_expiry_day);

                                        $passport_month_options    = generate_options($get_month_names, (array)$passport_expiry_month);

                                        $passport_year_options    = generate_options(get_years($now, $cutoff), (array)$passport_expiry_year);

                                        if (empty($passport_expiry_day) == false) {

                                            $passport_expiry_date = $passport_expiry_day . '-' . $passport_expiry_month . '-' . $passport_expiry_year;
                                        } else {

                                            $passport_expiry_date = '';
                                        }
                                        if (empty($passport_expiry_day) == true) {

                                            $passport_expiry_day = '';
                                        }
                                        if (empty($passport_expiry_month) == true) {

                                            $passport_expiry_month = '';
                                        }
                                        if (empty($passport_expiry_year) == true) {

                                            $passport_expiry_year = '';
                                        }



                                ?>

                                        <form action="<?= base_url() . 'index.php/user/update_traveller_details?active=traveller' ?>" method="post" name="update_traveller_details_from" autocomplete="off" id="update_traveller_details_from_<?= $traveller_k ?>">

                                            <input type="hidden" name="origin" value="<?= $origin ?>">

                                            <div class="trow">

                                                <div class="col-xs-8 col-md-3 tblpad halfwid"><span class="lavltr_mgc">Name</span> <span class="lavltr">

                                                        <?= $first_name ?>

                                                        <?= $last_name ?>

                                                    </span> </div>

                                                <div class="col-xs-4 col-md-2 tblpad halfwid"><span class="lavltr_mgc">DOB</span> <span class="lavltr">

                                                        <?= $date_of_birth ?>

                                                    </span> </div>

                                                <div class="col-xs-12 col-md-3 tblpad halfwid"><span class="lavltr_mgc">Email</span> <span class="lavltr">

                                                        <?= (empty($email) == false ? $email : '---') ?>

                                                    </span> </div>

                                                <div class="col-xs-12 col-md-2 tblpad halfwid hand-cursor bgformb">
                                                    <span class="lavltr pull-left arwrt">
                                                        <a class="detilac" data-toggle="collapse" data-target="#traveller_details_row<?= $traveller_k ?>" aria-expanded="true">Detail <i class="fa fa-chevron-down"></i></a>
                                                    </span>
                                                    <span class="lavltr pull-right">
                                                        <a href="<?= base_url() . 'index.php/user/delete_traveller/' . $origin . '' ?>" onclick="return confirm('are you sure you want to delete?')"><i class="fa fa-trash"></i></a>
                                                    </span>
                                                </div>


                                            </div>

                                            <div class="clearfix"></div>

                                            <div id="traveller_details_row<?= $traveller_k ?>" class="collapse">

                                                <div class="travemore">

                                                    <div class="othinformtn">

                                                        <ul class="nav nav-tabs tabssyb" role="tablist">

                                                            <li data-role="presentation" class="active"> <a href="#traveller_user_details<?= $traveller_k ?>" data-role="tab" data-toggle="tab">User Information</a> </li>

                                                            <li data-role="presentation" class=""> <a href="#traveller_passport_details<?= $traveller_k ?>" data-role="tab" data-toggle="tab">Passport Information</a>
                                                            </li>

                                                        </ul>

                                                        <div class="tab-content">

                                                            <div role="tabpanel" class="tab-pane active" id="traveller_user_details<?= $traveller_k ?>">

                                                                <div class="infowone">

                                                                    <div class="paspertorgnl">

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Name</div>

                                                                                <div class="lablmain cellpas">

                                                                                    <?= $first_name ?>

                                                                                    <?= $last_name ?>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">DOB</div>

                                                                                <div class="lablmain cellpas">

                                                                                    <?= $date_of_birth ?>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-12 col-md-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Email</div>

                                                                                <div class="lablmain cellpas">

                                                                                    <?= (empty($email) == false ? $email : '---') ?>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="clearfix"></div>

                                                                        <a class="editpasport">Edit</a>
                                                                    </div>

                                                                    <div class="clearfix"></div>

                                                                    <div class="paspertorgnl paspertedit">

                                                                        <div class="col-xs-4 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">First Name<span class="text-dange">*</span></div>

                                                                                <div class="lablmain cellpas">

                                                                                    <input name="traveller_first_name" id="traveller_first_name_<?= $traveller_k ?>" type="text" value="<?= $first_name ?>" class="clainput alpha no_copy_paste" placeholder="FirstName" required="" maxlength="20">
                                                                                    <span id="traveller_first_name_error_<?= $traveller_k ?>" style="color:red;"></span>
                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-4 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Last Name<span class="text-dange">*</span></div>

                                                                                <div class="lablmain cellpas">

                                                                                    <input name="traveller_last_name" id="traveller_last_name_<?= $traveller_k ?>" type="text" value="<?= $last_name ?>" class="clainput alpha no_space no_copy_paste" placeholder="LastName" required="" maxlength="20">
                                                                                    <span id="traveller_last_name_error_<?= $traveller_k ?>" style="color:red;"></span>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-4 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">DOB<span class="text-dange">*</span></span></div>

                                                                                <div class="lablmain cellpas">

                                                                                    <input name="traveller_date_of_birth" id="traveller_date_of_birth<?= $traveller_k ?>" type="text" value="<?= $date_of_birth ?>" class="clainput traveller_dob auto-datepicker disable-date-auto-update" placeholder="DOB" readonly required="">
                                                                                    <span id="add-travel-date-picker_error_<?= $traveller_k ?>" style="color:red;"></span>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-4 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Email <span class="text-danger">*</span></div>

                                                                                <div class="lablmain cellpas">

                                                                                    <input name="traveller_email" id="traveller_email_<?= $traveller_k ?>" type="text" value="<?= $email ?>" class="clainput validate_email" placeholder="Email" maxlength="40" required="">
                                                                                    <span id="traveller_email_error_<?= $traveller_k ?>" style="color:red;"></span>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="clearfix"></div>

                                                                        <button type="button" class="savepspot updatetraveller" id="updatetraveller_<?= $traveller_k ?>">Save</button>

                                                                        <a class="cancelll">Cancel</a>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <div role="tabpanel" class="tab-pane" id="traveller_passport_details<?= $traveller_k ?>">

                                                                <div class="infowone">

                                                                    <div class="paspertorgnl">

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Name</div>

                                                                                <div class="lablmain cellpas">

                                                                                    <?= (empty($passport_user_name) == false ? $passport_user_name : '---') ?>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Nationality</div>

                                                                                <div class="lablmain cellpas">

                                                                                    <?= (isset($iso_country_list[$passport_nationality]) == true ? $iso_country_list[$passport_nationality] : '---') ?>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Expiry Date</div>

                                                                                <div class="lablmain cellpas">

                                                                                    <?= (empty($passport_expiry_date) == false ? $passport_expiry_date : '---') ?>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Passport Number
                                                                                </div>

                                                                                <div class="lablmain cellpas">

                                                                                    <?= (empty($passport_number) == false ? $passport_number : '---') ?>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Issuing Country
                                                                                </div>

                                                                                <div class="lablmain cellpas">

                                                                                    <?= (isset($country_list[$passport_issuing_country]) == true ? $country_list[$passport_issuing_country] : '---') ?>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="clearfix"></div>

                                                                        <a class="editpasport">Edit</a>
                                                                    </div>

                                                                    <div class="clearfix"></div>

                                                                    <div class="paspertorgnl paspertedit">

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Name</div>

                                                                                <div class="lablmain cellpas">

                                                                                    <input type="text" name="passport_user_name" value="<?= $passport_user_name ?>" Placeholder="Name" class="clainput alpha" maxlength="20" />

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Nationality</div>

                                                                                <div class="lablmain cellpas">

                                                                                    <select name="passport_nationality" class="clainput">

                                                                                        <?= generate_options($iso_country_list, array(intval($passport_nationality) == 0 ? INDIA_CODE : $passport_nationality)) ?>

                                                                                    </select>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Expiry Date</div>

                                                                                <div class="lablmain cellpas">

                                                                                    <div class="retnmar">
                                                                                        <input name="traveller_passport_exp_date" id="add-travel-date-picker_passport_exp<?= $traveller_k ?>" type="text" class="disable-date-auto-update auto-datepicker clainput add-travel-date-picker_passport_exp" placeholder="Expiry Date" readonly required="required" value="<?= $passport_expiry_day . '-' . $passport_expiry_month . '-' . $passport_expiry_year ?>">

                                                                                        <!-- <div class="col-xs-4 splinmar">

                                        <select name="passport_expiry_day" class="clainput">

                                            <option value="">DD</option>

                                            <?= $passport_day_options; ?>

                                        </select>

                                        </div> -->

                                                                                        <!-- <div class="col-xs-4 splinmar">

                                        <select name="passport_expiry_month" class="clainput">

                                            <option value="">MM</option>

                                            <?= $passport_month_options; ?>

                                        </select>

                                        </div> -->

                                                                                        <!-- <div class="col-xs-4 splinmar">

                                        <select name="passport_expiry_year" class="clainput">

                                            <option value="">YYYY</option>

                                            <?= $passport_year_options; ?>

                                        </select>

                                        </div> -->

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Passport Number
                                                                                </div>

                                                                                <div class="lablmain cellpas">

                                                                                    <input name="passport_number" value="<?= $passport_number; ?>" maxlength="10" type="text" class="clainput" />

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="col-xs-6 margpas">

                                                                            <div class="tnlepasport">

                                                                                <div class="paspolbl cellpas">Issuing Country
                                                                                </div>

                                                                                <div class="lablmain cellpas">

                                                                                    <div class="selectwrp custombord">

                                                                                        <select name="passport_issuing_country" class="clainput">

                                                                                            <option value="">Please Select
                                                                                            </option>

                                                                                            <?= generate_options($country_list, array($passport_issuing_country)) ?>

                                                                                        </select>

                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <div class="clearfix"></div>

                                                                        <button type="submit" class="savepspot">Save</button>

                                                                        <a class="cancelll">Cancel</a>
                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </form>

                                    <?php }
                                } else { ?>

                                    <div class="col-md-12">

                                        <center>

                                            No Travellers Added

                                        </center>

                                    </div>

                                <?php } ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert-success').fadeOut('fast');
        }, 2000);


        $(".updatetraveller").click(function() {
            _status = 0;
            var buttonid = $(this).attr('id');
            var buttonid1 = buttonid.split("_");
            var num = buttonid1[1];
            // alert(("#traveller_first_name_"+num).value());
            if ($("#traveller_first_name_" + num).val() == "") {
                // alert();
                _status++;
                $("#traveller_first_name_error_" + num).text("Please Enter First Name");
            } else {
                $("#traveller_first_name_error_" + num).text("");
            }

            if ($("#traveller_last_name_" + num).val() == "") {
                // alert();
                _status++;
                $("#traveller_last_name_error_" + num).text("Please Enter Last Name");
            } else {
                $("#traveller_last_name_error_" + num).text("");
            }
            if ($("#traveller_date_of_birth" + num).val() == "") {
                // alert();
                _status++;
                $("#add-travel-date-picker_error_" + num).text("Please Enter DOB");
            } else {
                $("#add-travel-date-picker_error_" + num).text("");
            }
            if ($("#traveller_email_" + num).val() == "") {
                // alert();
                _status++;
                $("#traveller_email_error_" + num).text("Please Enter Email");
            } else {
                var email = $("#traveller_email_" + num).val();
                $("#traveller_email_error_" + num).text("");
                if (validate_email(email) == false) {

                    _status++;
                    $("#traveller_email_" + num).addClass('invalid-ip');
                    // $(this).addClass('invalid-ip');
                    $("#traveller_email_error_" + num).text("Invalid Email ID");

                }

            }

            if (_status == 0) {
                $("#update_traveller_details_from_" + num).submit();
            }

        })

        $('.remove_space').on('keypress', function(e) {
            if (e.which == 32) {
                return false;
            }
        });


        $('#add_traveller_btn').click(function(e) {
            // alert();

            e.preventDefault();

            var _status = true;

            var _focus = '';

            var email = $(this).closest('form').find('.validate_email').val().trim();

            $('input:required', $(this).closest('form')).each(function() {
                // alert(this.id);
                if (this.value == '') {

                    $(this).addClass('invalid-ip');

                    if (_status == true) {

                        _status = false;

                        _focus = this;

                    }

                    if (this.name == "traveller_first_name") {
                        $("#" + this.id + "_error").text("Please Enter First Name");
                    } else if (this.name == "traveller_last_name") {
                        $("#" + this.id + "_error").text("Please Enter Last Name");
                    } else if (this.name == "traveller_date_of_birth") {
                        $("#" + this.id + "_error").text("Please Enter DOB");
                    } else if (this.name == "traveller_email") {
                        $("#" + this.id + "_error").text("Please Enter Email ID");
                    }


                } else if ($(this).hasClass('invalid-ip')) {


                    $(this).removeClass('invalid-ip');


                } else if (this.value != "") {
                    $("#" + this.id + "_error").text('');
                }

            });

            if (email != '') {

                if (validate_email(email) == false) {

                    _status = false;
                    $("#traveller_email").addClass('invalid-ip');
                    // $(this).addClass('invalid-ip');
                    $("#traveller_email_error").text("Invalid Email ID");

                }

            }

            if (_status == true) {

                $(this).closest('form').submit();

            }

        });

        $('.validate_email').change(function() {

            var email = $(this).val().trim();

            if (email != '') {

                if (validate_email(email) == false) {

                    // $(this).val('').addClass('invalid-ip').attr('placeholder', 'Invalid Email ID');
                    $("#traveller_email").addClass('invalid-ip');
                    $("#traveller_email_error").text("Invalid Email ID");

                } else {

                    $(this).removeClass('invalid-ip');

                }

            }
            /*else {

            $(this).removeClass('invalid-ip');

        }*/

        });

        $('.editpasport').click(function() {

            $(this).parent().parent('.infowone').addClass('editsave');

        });

        $('.cancelll').click(function() {

            $(this).parent().parent('.infowone').removeClass('editsave');

        });

        $('.traveller_dob').each(function(e) {

            pastDatepicker($(this).attr('id'));

        });
        $('.add-travel-date-picker_passport_exp').each(function(e) {

            futureDatepicker($(this).attr('id'));

        });
        /*$('.add-travel-date-picker_passport_exp').datepicker({
        dateFormat:'dd-mm-yy',
        changeMonth:true,
        changeYear:true,
        minDate:0
    });*/

    });
</script>
<script>
    $(document).on("click", ".modal-body", function() {
        $("#add-travel-date-picker").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
<?php



$datepicker = array(array('add-travel-date-picker', PAST_DATE));
// $datepicker2 = array(array('add-travel-date-picker_passport_exp', FUTURE_DATE));

$GLOBALS['CI']->current_page->set_datepicker($datepicker);
// $GLOBALS['CI']->current_page->set_datepicker($datepicker2);

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
<script>
    $(document).ready(function() {
        $('#wallet_report').DataTable();
        $('.rewards_points_chg').on("change", function() {
            var price = $(this).find(':selected').attr('data-id');
            $(".ramount").val(price);
        });
        // changes added script for datatable
        $('#rewards_info_table,#getproduct_table').DataTable({

            "paging": true,
            "ordering": true,
            "info": true,
        });
    });
</script>
<script>
    function callCopy(element) {
        var codeCopy = document.getElementById('refercode');
        codeCopy.select();
        document.execCommand('copy');
    }
</script>