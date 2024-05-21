<?php
error_reporting(0);
$active_domain_modules = $this->active_domain_modules;
/**
 * Need to make privilege based system
 * Privilege only for loading menu and access of the web page
 * 
 * Data loading will not be based on privilege.
 * Data loading logic will be different.
 * It depends on many parameters
 */
$menu_list = array();
if (count($active_domain_modules) > 0) {
    $any_domain_module = true;
} else {
    $any_domain_module = false;
}
$airline_module = is_active_airline_module();
$accomodation_module = is_active_hotel_module();
$bus_module = is_active_bus_module();
$package_module = is_active_package_module();
$sightseen_module = is_active_sightseeing_module();
$car_module = is_active_car_module();
$transferv1_module = is_active_transferv1_module();
$bb = 'b2b';
$bc = 'b2c';
$subadmin = 'sub admin';

$b2b = is_active_module($bb);
$b2c = is_active_module($bc);
$subadmin = is_active_module($subadmin);
//checking social login status 
$social_login = 'facebook';
$social = is_active_social_login($social_login);
//echo "ela".$accomodation_module;exit;
$accomodation_module = 1;
?>
<ul class="sidebar-menu" id="magical-menu">
    <?php if (check_user_previlege('p1')) : ?>
        <li class="treeview">
            <a href="<?php echo base_url() ?>">
                <i class="far fa-tachometer-alt"></i> <span>Dashboard</span> </a>
        </li>
    <?php endif; ?>
    <?php if (is_domain_user() == false) { // ACCESS TO ONLY PROVAB ADMIN 
    ?>
        <li class="treeview">
            <a href="#">
                <i class="far fa-wrench"></i> <span>Management</span> <i class="far fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo base_url() . 'index.php/user/user_management' ?>"><i class="far fa-user"></i> User</a></li>
                <li><a href="<?php echo base_url() . 'index.php/user/domain_management' ?>"><i class="far fa-laptop"></i> Domain</a></li>
                <li><a href="<?php echo base_url() . 'index.php/module/module_management' ?>"><i class="far fa-sitemap"></i> Master Module</a></li>
            </ul>
        </li>
        <?php if ($any_domain_module) { ?>
            <li class="treeview">
                <a href="#">
                    <i class="far fa-user"></i> <span>Markup</span> <i class="far fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <?php if ($airline_module) { ?>
                        <li><a href="<?php echo base_url() . 'index.php/private_management/airline_domain_markup' ?>"><i class="<?= get_arrangement_icon(META_AIRLINE_COURSE) ?>"></i> Flight</a></li>
                    <?php } ?>
                    <?php if ($accomodation_module) { ?>
                        <li><a href="<?php echo base_url() . 'index.php/private_management/hotel_domain_markup' ?>"><i class="<?= get_arrangement_icon(META_ACCOMODATION_COURSE) ?>"></i> Hotel</a></li>
                    <?php } ?>
                    <?php if ($bus_module) { ?>
                        <li><a href="<?php echo base_url() . 'index.php/private_management/bus_domain_markup' ?>"><i class="<?= get_arrangement_icon(META_BUS_COURSE) ?>"></i> Bus</a></li>
                    <?php } ?>
                    <?php if ($transferv1_module) { ?>
                        <li><a href="<?php echo base_url() . 'index.php/private_management/transfer_domain_markup' ?>"><i class="<?= get_arrangement_icon(META_TRANSFERV1_COURSE) ?>"></i>Transfers</a></li>
                    <?php } ?>

                    <?php if ($sightseen_module) { ?>
                        <li><a href="<?php echo base_url() . 'index.php/private_management/sightseeing_domain_markup' ?>"><i class="<?= get_arrangement_icon(META_SIGHTSEEING_COURSE) ?>"></i>Activities</a></li>
                    <?php } ?>

                </ul>
            </li>
        <?php } ?>
        <li class="treeview">
            <a href="<?php echo base_url() . 'index.php/private_management/process_balance_manager' ?>">
                <i class="far fa-google-wallet"></i>
                <span> Master Balance Manager </span>
            </a>
        </li>
        <li class="treeview">
            <a href="<?php echo base_url() . 'index.php/private_management/event_logs' ?>">
                <i class="far fa-shield"></i>
                <span> Event Logs </span>
            </a>
        </li>
    <?php
    } else if ((is_domain_user() == true)) {
        // ACCESS TO ONLY DOMAIN ADMIN
    ?>
        <!-- USER ACCOUNT MANAGEMENT -->
        <?php if (check_user_previlege('p2')) : ?>
            <li class="treeview">
                <a href="#">
                    <i class="far fa-user"></i>
                    <span> Users </span><i class="far fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <!-- USER TYPES -->
                    <?php if ($b2c) {
                        if (check_user_previlege('p17')) : ?>
                            <li><a href="<?php echo base_url() . 'index.php/user/b2c_user?filter=user_type&q=' . B2C_USER; ?>"><i class="far fa-circle"></i> B2C</a>
                                <ul class="treeview-menu">
                                    <li><a href="<?php echo base_url() . 'index.php/user/b2c_user?filter=user_type&q=' . B2C_USER . '&user_status=' . ACTIVE; ?>"><i class="far fa-check"></i> Active</a></li>
                                    <li><a href="<?php echo base_url() . 'index.php/user/b2c_user?filter=user_type&q=' . B2C_USER . '&user_status=' . INACTIVE; ?>"><i class="far fa-times"></i> InActive</a></li>
                                    <li><a href="<?php echo base_url() . 'index.php/user/get_logged_in_users?filter=user_type&q=' . B2C_USER; ?>"><i class="far fa-circle"></i> Logged In User</a></li>
                                </ul>
                            </li>
                    <?php endif;
                    } ?>
                    <?php if ($b2b) {
                        if (check_user_previlege('p24')) : ?>
                            <li><a href="<?php echo base_url() . 'index.php/user/b2b_user?filter=user_type&q=' . B2B_USER ?>"><i class="far fa-circle"></i> Agents</a>
                                <ul class="treeview-menu">
                                    <li><a href="<?php echo base_url() . 'index.php/user/b2b_user?user_status=' . ACTIVE; ?>"><i class="far fa-check"></i> Active</a></li>
                                    <li><a href="<?php echo base_url() . 'index.php/user/b2b_user?user_status=' . INACTIVE; ?>"><i class="far fa-times"></i> InActive</a></li>
                                    <li><a href="<?php echo base_url() . 'index.php/user/get_logged_in_users?filter=user_type&q=' . B2B_USER; ?>"><i class="far fa-circle"></i> Logged In User</a></li>
                                </ul>
                            </li>
                            <?php if ($b2b) {
                                if (check_user_previlege('p142')) : ?>

                                    <li><a href="<?php echo base_url() . 'index.php/user/b2b_user?filter=user_type&q=' . SUPPLIER ?>"><i class="far fa-circle"></i> Supplier</a>
                                        <ul class="treeview-menu">
                                            <li><a href="<?php echo base_url() . 'index.php/user/b2as_user?filter=user_type&q=' . SUPPLIER . '&user_status=' . ACTIVE; ?>"><i class="fa fa-check"></i> Active</a></li>
                                            <li><a href="<?php echo base_url() . 'index.php/user/b2as_user?filter=user_type&q=' . SUPPLIER . '&user_status=' . INACTIVE; ?>"><i class="fa fa-times"></i> InActive</a></li>
                                        </ul>
                                    </li>
                            <?php endif;
                            } ?>
                        <?php endif;
                    }
                    if (check_user_previlege('p73')) : ?>
                        <li><a href="<?php echo base_url() . 'index.php/user/user_management?filter=user_type&q=' . SUB_ADMIN ?>"><i class="far fa-circle"></i> Sub Admin</a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo base_url() . 'index.php/user/user_management?filter=user_type&q=' . SUB_ADMIN . '&user_status=' . ACTIVE; ?>"><i class="far fa-check"></i> Active</a></li>
                                <li><a href="<?php echo base_url() . 'index.php/user/user_management?filter=user_type&q=' . SUB_ADMIN . '&user_status=' . INACTIVE; ?>"><i class="far fa-times"></i> InActive</a></li>
                                <li><a href="<?php echo base_url() . 'index.php/user/get_logged_in_users?filter=user_type&q=' . SUB_ADMIN; ?>"><i class="far fa-circle"></i> Logged In User</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif;
        // changes start user crm: added following code block 
        if (check_user_previlege('p2')) : ?>
            <li class="treeview">
                <a href="#">
                    <i class="far fa-user"></i>
                    <span> User CRM </span><i class="far fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <!-- USER TYPES -->
                    <?php if ($b2c) {
                        if (check_user_previlege('p17')) : ?>
                            <li><a href="<?php echo base_url() . 'index.php/user/b2c_user?filter=user_type&q=' . B2C_USER; ?>"><i class="far fa-circle"></i> B2C</a>
                                <ul class="treeview-menu">
                                    <li><a href="<?php echo base_url() . 'index.php/user/user_data_crm?filter=user_type&q=' . B2C_USER . '&user_status=' . ACTIVE; ?>"><i class="far fa-check"></i> Active</a></li>
                                    <li><a href="<?php echo base_url() . 'index.php/user/user_data_crm?filter=user_type&q=' . B2C_USER . '&user_status=' . INACTIVE; ?>"><i class="far fa-times"></i> InActive</a></li>
                                </ul>
                            </li>
                    <?php endif;
                    } ?>
                    <?php if ($b2b) {
                        if (check_user_previlege('p24')) : ?>
                            <li><a href="<?php echo base_url() . 'index.php/user/b2b_user?filter=user_type&q=' . B2B_USER ?>"><i class="far fa-circle"></i> Agents</a>
                                <ul class="treeview-menu">
                                    <li><a href="<?php echo base_url() . 'index.php/user/user_data_crm?filter=user_type&q=' . B2B_USER . '&user_status=' . ACTIVE; ?>"><i class="far fa-check"></i> Active</a></li>
                                    <li><a href="<?php echo base_url() . 'index.php/user/user_data_crm?filter=user_type&q=' . B2B_USER . '&user_status=' . INACTIVE; ?>"><i class="far fa-times"></i> InActive</a></li>
                                </ul>
                            </li>
                    <?php endif;
                    } ?>
                </ul>
            </li>
            <?php endif;
        // changes end user crm: added following code block
        if ($any_domain_module) {
            if (check_user_previlege('p3')) : ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fas fa-shield"></i>
                        <span> Queues </span><i class="far fa-angle-left pull-right"></i>
                    </a>
                    <?php if (check_user_previlege('p71')) : ?>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url() . 'index.php/report/cancellation_queue/'; ?>"><i class="far fa-flight"></i> Flight Cancellation </a>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endif;
            if (check_user_previlege('p4')) : ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fas fa-chart-bar"></i>
                        <span> Reports </span><i class="far fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <!-- USER TYPES -->
                        <?php if (check_user_previlege('p74')) : ?>
                            <li><a href="#"><i class="far fa-circle"></i> B2C</a>
                                <ul class="treeview-menu">
                                    <?php if ($airline_module) {
                                        if (check_user_previlege('p18')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2c_flight_report/'; ?>"><i class="far fa-plane"></i> Flight</a></li>
                                    <?php endif;
                                    } ?>
                                    <?php if ($accomodation_module) {
                                        if (check_user_previlege('p19')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2c_hotel_report/'; ?>"><i class="far fa-bed"></i> Hotel</a></li>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2c_hotelcrs_report/'; ?>"><i class="far fa-bed"></i>Hotel_crs</a></li>
                                    <?php endif;
                                    } ?>
                                    <?php if ($bus_module) {
                                        if (check_user_previlege('p20')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2c_bus_report/'; ?>"><i class="<?= get_arrangement_icon(META_BUS_COURSE) ?>"></i> Bus</a></li>
                                    <?php endif;
                                    } ?>

                                    <?php if ($transferv1_module) {
                                        if (check_user_previlege('p21')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2c_transfers_report_crs/'; ?>"><i class="<?= get_arrangement_icon(META_TRANSFERV1_COURSE) ?>"></i> Transfer CRS</a></li>

                                    <?php endif;
                                    }
                                    ?>

                                    <?php if ($sightseen_module) {
                                        if (check_user_previlege('p22')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2c_activitiescrs_report/'; ?>"><i class="<?= get_arrangement_icon(META_SIGHTSEEING_COURSE) ?>"></i> Activities CRS</a></li>

                                    <?php endif;
                                    } ?>

                                    <?php if ($package_module) {
                                        if (check_user_previlege('p24')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2c_holiday_report/'; ?>"><i class="<?= get_arrangement_icon(META_PACKAGE_COURSE) ?>"></i> Holiday</a></li>
                                    <?php endif;
                                    } ?>
                                    <!-- <li><a href="<?php echo base_url() . 'index.php/cms/plan_retirement' ?>"><i class="far fa-chart-pie"></i> <span>Investor Booking</span></a></li> -->


                                </ul>
                            </li>
                        <?php endif;
                        if (check_user_previlege('p75')) : ?>
                            <li><a href="#"><i class="far fa-circle"></i> Agent</a>
                                <ul class="treeview-menu">
                                    <?php if ($airline_module) {
                                        if (check_user_previlege('p25')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2b_flight_report/'; ?>"><i class="far fa-plane"></i> Flight</a></li>
                                    <?php endif;
                                    } ?>
                                    <?php if ($accomodation_module) {
                                        if (check_user_previlege('p26')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2b_hotel_report/'; ?>"><i class="far fa-bed"></i> Hotel</a></li>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2b_hotelcrs_report/'; ?>"><i class="far fa-bed"></i>Hotel_crs</a></li>
                                    <?php endif;
                                    } ?>
                                    <?php if ($bus_module) {
                                        if (check_user_previlege('p27')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2b_bus_report/'; ?>"><i class="<?= get_arrangement_icon(META_BUS_COURSE) ?>"></i> Bus</a></li>
                                        <?php endif;
                                    }
                                    if ($transferv1_module) {
                                        if (check_user_previlege('p28')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2b_transfers_report_crs/'; ?>"><i class="<?= get_arrangement_icon(META_TRANSFERV1_COURSE) ?>"></i>Transfers CRS</a></li>
                                    <?php endif;
                                    } ?>

                                    <?php if ($sightseen_module) {
                                        if (check_user_previlege('p29')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2b_activitiescrs_report/'; ?>"><i class="<?= get_arrangement_icon(META_SIGHTSEEING_COURSE) ?>"></i> Activities CRS</a></li>
                                    <?php endif;
                                    } ?>

                                    <?php if ($package_module) {
                                        if (check_user_previlege('p31')) : ?>
                                            <li><a href="<?php echo base_url() . 'index.php/report/b2b_holiday_report/'; ?>"><i class="<?= get_arrangement_icon(META_PACKAGE_COURSE) ?>"></i> Holiday</a></li>
                                    <?php endif;
                                    } ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                    </ul>
                    <ul class="treeview-menu">
                        <!--  TYPES -->
                        <li class="treeview">
                            <a href="<?php echo base_url() . 'index.php/transaction/logs' ?>">
                                <i class="far fa-shield"></i>
                                <span> Transaction Logs </span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="<?php echo base_url() . 'index.php/transaction/search_history' ?>">
                                <i class="far fa-search"></i>
                                <span> Search History </span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="<?php echo base_url() . 'index.php/transaction/top_destinations' ?>">
                                <i class="far fa-globe"></i>
                                <span> Top Destinations</span>
                            </a>
                        </li>
                        <li class="treeview">
                            <a href="<?php echo base_url() . 'index.php/management/account_ledger' ?>">
                                <i class="fas fa-chart-bar "></i>
                                <span> Account Ledger</span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif;
            if (check_user_previlege('p5')) : ?>
                <li class="treeview">
                    <a href="#">
                        <i class="far fa-money-bill"></i> <span>Account</span> <i class="far fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (check_user_previlege('p31')) : ?>
                            <li><a href="<?php echo base_url() . 'private_management/credit_balance' ?>"><i class="far fa-circle"></i> Credit Balance</a></li>
                        <?php endif;
                        if (check_user_previlege('p32')) : ?>
                            <li><a href="<?php echo base_url() . 'private_management/debit_balance' ?>"><i class="far fa-circle"></i> Debit Balance</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif;
            if (check_user_previlege('p7')) : ?>
                <li><a href="<?php echo base_url() . 'index.php/management/segment_wise_discount/'; ?>"><i class="<?= get_arrangement_icon(META_AIRLINE_COURSE) ?>"></i> Segment Wise Discount</a></li>
                <li class="treeview">
                    <a href="#">
                        <i class="far fa-plus-square"></i>
                        <span> Markup </span><i class="far fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <!-- Markup TYPES -->
                        <?php if ($b2c) {
                            if (check_user_previlege('p35')) : ?>
                                <li><a href="#"><i class="far fa-circle"></i> B2C</a>
                                    <ul class="treeview-menu">
                                        <?php if ($airline_module) {
                                            if (check_user_previlege('p76')) : ?>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2c_airline_markup/'; ?>"><i class="<?= get_arrangement_icon(META_AIRLINE_COURSE) ?>"></i> TMX Flight</a></li>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2c_amadeus_airline_markup/'; ?>"><i class="<?= get_arrangement_icon(META_AIRLINE_COURSE) ?>"></i>Amadeus Flight</a></li>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2c_plazma_airline_markup/'; ?>"><i class="<?= get_arrangement_icon(META_AIRLINE_COURSE) ?>"></i>Plazma Flight</a></li>
                                        <?php endif;
                                        } ?>
                                        <?php if ($accomodation_module) {
                                            if (check_user_previlege('p77')) : ?>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2c_hotel_markup/'; ?>"><i class="<?= get_arrangement_icon(META_ACCOMODATION_COURSE) ?>"></i> Hotel</a></li>
                                                <!-- <li><a href="<?php echo base_url() . 'index.php/management/b2c_hotelcrs_markup/'; ?>"><i class="<?= get_arrangement_icon(META_ACCOMODATION_COURSE) ?>"></i> Hotel CRS</a></li> -->
                                        <?php endif;
                                        } ?>
                                        <?php if ($bus_module) {
                                            if (check_user_previlege('p78')) : ?>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2c_bus_markup/'; ?>"><i class="<?= get_arrangement_icon(META_BUS_COURSE) ?>"></i> Bus</a></li>
                                        <?php endif;
                                        } ?>

                                        <?php if ($transferv1_module) {
                                            if (check_user_previlege('p79')) : ?>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2c_transfer_markup/'; ?>"><i class="<?= get_arrangement_icon(META_TRANSFERV1_COURSE) ?>"></i> Transfers CRS</a></li>


                                        <?php endif;
                                        }
                                        ?>


                                        <?php if ($sightseen_module) {
                                            if (check_user_previlege('p80')) : ?>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2c_sightseeing_markup/'; ?>"><i class="<?= get_arrangement_icon(META_SIGHTSEEING_COURSE) ?>"></i> Activities CRS</a></li>

                                        <?php endif;
                                        }
                                        ?>

                                        <?php if ($package_module) {
                                            if (check_user_previlege('p129')) : ?>
                                                <!-- <li><a href="<?php echo base_url() . 'index.php/management/package_domain_markup/'; ?>"><i class="<?= get_arrangement_icon(META_PACKAGE_COURSE) ?>"></i> Tour</a></li> -->

                                        <?php endif;
                                        }
                                        ?>

                                    </ul>
                                </li>
                            <?php endif;
                        }
                        if ($b2b) {
                            if (check_user_previlege('p36')) :
                            ?>
                                <li><a href="#"><i class="far fa-circle"></i> B2B</a>
                                    <ul class="treeview-menu">
                                        <?php if ($airline_module) {
                                            if (check_user_previlege('p82')) : ?>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2b_airline_markup/'; ?>"><i class="<?= get_arrangement_icon(META_AIRLINE_COURSE) ?>"></i> TMX Flight</a></li>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2b_amadeus_airline_markup/'; ?>"><i class="<?= get_arrangement_icon(META_AIRLINE_COURSE) ?>"></i> Amadeus Flight</a></li>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2b_plazma_airline_markup/'; ?>"><i class="<?= get_arrangement_icon(META_AIRLINE_COURSE) ?>"></i> Plazma Flight</a></li>
                                        <?php endif;
                                        } ?>
                                        <?php if ($accomodation_module) {
                                            if (check_user_previlege('p83')) : ?>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2b_hotel_markup/'; ?>"><i class="<?= get_arrangement_icon(META_ACCOMODATION_COURSE) ?>"></i> Hotel</a></li>
                                                <!-- <li><a href="<?php echo base_url() . 'index.php/management/b2b_hotelcrs_markup/'; ?>"><i class="<?= get_arrangement_icon(META_ACCOMODATION_COURSE) ?>"></i> Hotel CRS</a></li> -->
                                        <?php endif;
                                        } ?>
                                        <?php if ($bus_module) {
                                            if (check_user_previlege('p35')) : ?>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2b_bus_markup/'; ?>"><i class="<?= get_arrangement_icon(META_BUS_COURSE) ?>"></i> Bus</a></li>
                                        <?php endif;
                                        } ?>

                                        <?php if ($transferv1_module) {
                                            if (check_user_previlege('p85')) : ?>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2b_transfer_markup/'; ?>"><i class="<?= get_arrangement_icon(META_TRANSFERV1_COURSE) ?>"></i> Transfers CRS</a></li>

                                        <?php endif;
                                        }
                                        ?>


                                        <?php if ($sightseen_module) {
                                            if (check_user_previlege('p86')) : ?>
                                                <li><a href="<?php echo base_url() . 'index.php/management/b2b_sightseeing_markup/'; ?>"><i class="<?= get_arrangement_icon(META_SIGHTSEEING_COURSE) ?>"></i> Activities CRS</a></li>

                                        <?php endif;
                                        }
                                        ?>

                                        <?php if ($package_module) {
                                            if (check_user_previlege('p130')) : ?>
                                                <!-- <li><a href="<?php echo base_url() . 'index.php/management/b2bpackage_domain_markup/'; ?>"><i class="<?= get_arrangement_icon(META_PACKAGE_COURSE) ?>"></i> Tour</a></li> -->

                                        <?php endif;
                                        }
                                        ?>


                                    </ul>
                                </li>
                        <?php endif;
                        } ?>
                    </ul>
                </li>

            <?php endif;
        }
        if (check_user_previlege('p8')) : ?>
            <!--<li class="treeview">
            <a href="<?php echo base_url() . 'index.php/management/gst_master' ?>">
                <i class="fa fa-globe"></i> 
                <span> GST Master </span>
            </a>
        </li>-->
            <?php endif;
        if ($b2b) {
            if (check_user_previlege('p9')) : ?>
                <li class="treeview">
                    <a href="#">
                        <i class="far fa-money-bill"></i>
                        <span> Master Balance Manager </span><i class="far fa-angle-left pull-right"></i>
                    </a>
                    <?php if (check_user_previlege('p37')) : ?>
                        <ul class="treeview-menu">
                            <!-- USER TYPES -->
                            <!--<li><a href="<?php echo base_url() . 'index.php/management/master_balance_manager' ?>"><i class="far fa-circle-o"></i> API</a></li>-->
                            <li><a href="<?php echo base_url() . 'index.php/management/b2b_balance_manager' ?>"><i class="far fa-circle"></i> B2B</a></li>
                        </ul>
                    <?php endif;
                    if (check_user_previlege('p38')) : ?>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url() . 'index.php/management/b2b_credit_request' ?>"><i class="far fa-circle"></i> B2B Credit Limt Requests</a></li>
                        </ul>
                        <!-- new option for b2b topup reports -->
                        <ul class="treeview-menu">
                            <li><a href="<?php echo base_url() . 'index.php/management/b2b_topup_report' ?>"><i class="far fa-circle"></i> B2B Topup Report</a></li>
                        </ul>
                    <?php endif; ?>
                </li>

            <?php endif;
        }
        if ($package_module) {
            if (check_user_previlege('p120')) : ?>
                <!--  <li class="treeview">
                <a href="#">
                    <i class="far fa-plus-square"></i> 
                    <span> Package Management </span><i class="far fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    

                    <?php if (check_user_previlege('p39')) : ?>
                        <li>
                        <a href="<?php echo base_url() . 'index.php/supplier/view_packages_types' ?>"><i class="far fa-circle"></i> View Package Types </a>
                        </li>
                    <?php endif;
                    if (check_user_previlege('p40')) : ?>
                    
                    <li>
                    <a href="<?php echo base_url() . 'index.php/supplier/add_with_price' ?>"><i class="far fa-circle"></i> Add New Package </a>
                    </li>
                    <?php endif;
                    if (check_user_previlege('p41')) : ?>
                    <li><a href="<?php echo base_url() . 'index.php/supplier/view_with_price' ?>"><i class="far fa-circle"></i> View Packages </a></li>
                    <?php endif;
                    if (check_user_previlege('p42')) : ?>
                    <li><a href="<?php echo base_url() . 'index.php/supplier/enquiries' ?>"><i class="far fa-circle"></i> View Packages Enquiries </a></li>
                    <?php endif; ?>
                </ul>
            </li> -->
                <?php if (check_user_previlege('p157')) { ?>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-trophy blue"></i> <span>Rewards</span><i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if (check_user_previlege('p158')) { ?>
                                <li><a href="<?php echo base_url() . 'index.php/reward/add_rewards' ?>"><i class="far fa-circle"></i> Add/Manage Rewards</a></li>
                            <?php } ?>
                            <?php if (check_user_previlege('p160')) { ?>
                                <li><a href="<?php echo base_url() . 'index.php/reward/reward_range' ?>"><i class="far fa-circle"></i> Set reward Range</a></li>
                            <?php } ?>
                            <?php if (check_user_previlege('p161')) { ?>
                                <li><a href="<?php echo base_url() . 'index.php/reward/reward_conversion' ?>"><i class="far fa-circle"></i> Rewards Conversion</a></li>
                            <?php } ?>
                            <?php if (check_user_previlege('p162')) { ?>
                                <li class=""><a href="<?php echo base_url() . 'index.php/loyalty_program/product_list' ?>"><i class="far fa-circle"></i> <span>Product List</span></a></li>
                            <?php } ?>
                            <?php if (check_user_previlege('p163')) { ?>
                                <li><a href="<?php echo base_url() . 'index.php/reward/wallet_settings' ?>"><i class="far fa-circle"></i>Wallet Settings</a></li>
                            <?php } ?>
                            <?php if (check_user_previlege('p164')) { ?>
                                <li><a href="<?php echo base_url() . 'index.php/reward/wallet_transaction' ?>"><i class="far fa-circle"></i> Wallet Transaction</a></li>
                            <?php } ?>
                            <?php if (check_user_previlege('p165')) { ?>

                                <li><a href="<?php echo base_url() . 'index.php/reward/referral_report' ?>"><i class="far fa-circle"></i>Referral</a></li>
                            <?php } ?>
                            <?php if (check_user_previlege('p166')) { ?>
                                <li><a href="<?php echo base_url() . 'index.php/reward/reward_report' ?>"><i class="far fa-circle"></i> Rewards Report</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <!--<li class="treeview "><a href="#"> <i class="far fa-users"></i> <span>Agent Loyalty Program</span><i class="fa fa-angle-left pull-right"></i>
   </a>
      <ul class="treeview-menu">   
            <li class=""><a href="<?php echo base_url() . 'index.php/user/b2b_reward_user/' ?>"><i class="fa fa-pencil-square"></i> <span>Master Modules</span></a></li>
             <li class=""><a href="<?php echo base_url() . 'index.php/loyalty_program/currency_rate' ?>"><i class="fa fa-pencil-square"></i> <span>Currency rate</span></a></li>
            <li class=""><a href="<?php echo base_url() . 'index.php/loyalty_program/get_total_reward_agent' ?>"><i class="fa fa-pencil-square"></i> <span>Agent Reward Point</span></a></li>
             <li class=""><a href="<?php echo base_url() . 'index.php/loyalty_program/get_total_reward_point' ?>"><i class="fa fa-pencil-square"></i> <span>Total Reward Point</span></a></li>
             <li class=""><a href="<?php echo base_url() . 'index.php/loyalty_program/product_list' ?>"><i class="fa fa-pencil-square"></i> <span>Product List</span></a></li>
              <li class=""><a href="<?php echo base_url() . 'index.php/loyalty_program/redeem_request' ?>"><i class="fa fa-pencil-square"></i> <span>Redeem Request</span></a></li>
               <li class=""><a href="<?php echo base_url() . 'index.php/loyalty_program/hold_reward_point' ?>"><i class="fa fa-pencil-square"></i> <span>Hold reward point</span></a></li>
          
         </ul>
      </li>-->
                <?php if (check_user_previlege('p134')) { ?>
                    <li class="treeview ">
                        <a href="#"> <i class="fa fa-hourglass"></i> <span>
                                Activities Management </span><i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <!-- USER TYPES -->
                            <!-- <li><a href="<?php echo base_url() . 'index.php/activity/enquiries' ?>"><i
            class="fas fa-circle-notch"></i> View Excursion Enquiries </a></li> -->
                            <?php if (check_user_previlege('p135')) { ?>
                                <li><a href="<?php echo base_url() . 'index.php/activity/add_with_price' ?>"><i class="fas fa-circle-notch"></i> Add Activities </a></li>
                            <?php } ?>
                            <!-- <li><a
            href="<?php echo base_url() . 'index.php/activity/view_with_price' ?>"><i
            class="fas fa-circle-notch"></i> View Excursion </a></li> -->
                            <?php if (check_user_previlege('p136')) { ?>
                                <li><a href="<?php echo base_url() . 'index.php/activity/cancellation_policy' ?>"><i class="fas fa-circle-notch"></i> Cancellation Policy</a></li>
                            <?php } ?>
                            <?php if (check_user_previlege('p137')) { ?>
                                <li><a href="<?php echo base_url() . 'index.php/activity/view_with_price' ?>"><i class="fa fa-circle-notch"></i>Excursion List</a></li>
                                <li><a href="<?php echo base_url() . 'index.php/activity/view_with_price_supplier' ?>"><i class="fa fa-circle-notch"></i>Supplier Excursion List</a></li>
                            <?php } ?>
                            <?php if (check_user_previlege('p138')) { ?>
                                <li class="treeview">
                                    <a href="#">
                                        <i class="fa fa-plus-square"></i>
                                        <span>Master Management</span><i class="fa fa-angle-left pull-right"></i>
                                    </a>
                                    <ul class="treeview-menu">

                                        <li><a href="<?php echo base_url() . 'index.php/activity/view_packages_types' ?>"><i class="fas fa-circle-notch"></i> Activities Types </a></li>

                                        <li><a href="<?php echo base_url() . 'index.php/activity/activity_subtheme' ?>"><i class="fas fa-circle-notch"></i> Activities Themes</a></li>
                                        <li><a href="<?php echo base_url() . 'index.php/activity/activity_amenties' ?>"><i class="fas fa-circle-notch"></i> Excursion Amenties</a></li>
                                        <li><a href="<?php echo base_url() . 'index.php/activity/health_instructions' ?>"><i class="fas fa-circle-notch"></i> Health Restriction</a></li>
                                        <li class="treeview">
                                            <a href="#">
                                                <i class="fa fa-plus-square"></i>
                                                <span>Nationality Management </span><i class="fa fa-angle-left pull-right"></i>
                                            </a>
                                            <ul class="treeview-menu">
                                                <li>
                                                    <a href="<?php echo base_url() . 'index.php/activity/nationality_region' ?>"><i class="fa fa-circle-notch"></i>Region</a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo base_url() . 'index.php/activity/view_notionality_country' ?>"><i class="fa fa-circle-notch"></i>Country</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <li class="treeview">
                    <a href="#"> <i class="far fa-plus-square"></i> <span>
                            Tour CRS </span><i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <?php if (check_user_previlege('p121')) : ?>
                            <li class=""><a href="<?php echo base_url() . 'index.php/tours/tour_list' ?>">
                                    <i class="fas fa-circle-notch"></i> Tour List </a></li>
                        <?php endif;
                        if (check_user_previlege('p122')) : ?>
                            <!--             <li class=""><a href="--><?php //echo base_url().'index.php/tours/agent_tour_list'
                                                                        ?><!--">-->
                            <!--            <i class="fas fa-circle-notch"></i> Agent Holiday List </a></li>    -->
                            <!--                        <li class=""><a href="--><?php //echo base_url().'index.php/tours/tour_list_pending'
                                                                                    ?><!--">-->
                            <!--            <i class="fas fa-circle-notch"></i>Unapproved Holiday List </a></li>-->
                            <!-- <li class=""><a href="<?php echo base_url() . 'index.php/tours/tours_enquiry'; ?>">
                        <i class="fas fa-circle-notch"></i> Inquiry</a></li> -->
                            <!-- <li class=""><a href="<?php echo base_url() . 'index.php/tours/quotation_list'; ?>">
                        <i class="fas fa-circle-notch"></i> Quotation List</a></li> -->

                            <li class=""><a href="<?php echo base_url() . 'index.php/tours/tour_type' ?>">
                                    <i class="fas fa-circle-notch"></i>Activities </a></li>
                        <?php endif;
                        if (check_user_previlege('p123')) : ?>

                            <li class=""><a href="<?php echo base_url() . 'index.php/tours/tour_subtheme' ?>">
                                    <i class="fas fa-circle-notch"></i> Theme Type</a></li>
                        <?php endif;
                        if (check_user_previlege('p124')) : ?>
                            <li class=""><a href="<?php echo base_url() . 'index.php/tours/tour_region' ?>">
                                    <i class="fas fa-circle-notch"></i> Continent </a></li>
                        <?php endif;
                        if (check_user_previlege('p125')) : ?>
                            <li class=""><a href="<?php echo base_url() . 'index.php/tours/tour_country' ?>">
                                    <i class="fas fa-circle-notch"></i> Country </a></li>
                        <?php endif;
                        if (check_user_previlege('p126')) : ?>
                            <li class=""><a href="<?php echo base_url() . 'index.php/tours/tour_city' ?>">
                                    <i class="fas fa-circle-notch"></i> City </a></li>
                        <?php endif;
                        if (check_user_previlege('p127')) : ?>

                            <li><a href="<?php echo base_url() . 'index.php/supplier/enquiries' ?>"><i class="far fa-circle"></i> View Packages Enquiries </a></li>
                        <?php endif;
                        if (check_user_previlege('p128')) : ?>
                            <li><a href="<?php echo base_url() . 'index.php/supplier/general_enquiries' ?>"><i class="far fa-circle"></i> General Enquiries </a></li>
                        <?php endif; ?>
                        <?php if (check_user_previlege('p154')) : ?>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-plus-square"></i>
                                    <span>Nationality Management </span><i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <?php if (check_user_previlege('p155')) : ?>
                                        <li>
                                            <a href="<?php echo base_url() . 'index.php/tours/nationality_region' ?>"><i class="fa fa-circle-notch"></i>Region</a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (check_user_previlege('p156')) : ?>
                                        <li>
                                            <a href="<?php echo base_url() . 'index.php/tours/view_notionality_country' ?>"><i class="fa fa-circle-notch"></i>Country</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
            <?php endif;
        }
        if (check_user_previlege('p11')) : ?>
            <li class="treeview">
                <a href="#">
                    <i class="far fa-envelope"></i>
                    <span> Email Subscriptions </span><i class="far fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <!-- USER TYPES -->
                    <li><a href="<?php echo base_url() . 'index.php/general/view_subscribed_emails' ?>"><i class="far fa-circle"></i> View Emails </a></li>
                    <!-- <li><a href="<?php echo base_url() . 'index.php/supplier/add_with_price' ?>"><i class="far fa-circle"></i> Add New Package </a></li>
                <li><a href="<?php echo base_url() . 'index.php/supplier/view_with_price' ?>"><i class="far fa-circle"></i> View Packages </a></li>
                <li><a href="<?php echo base_url() . 'index.php/supplier/enquiries' ?>"><i class="far fa-circle"></i> View Packages Enquiries </a></li> -->
                </ul>
            </li>
    <?php endif;
    } ?>
    <li class="treeview">
        <?php if (check_user_previlege('p143')) {
        ?>
            <a href="#"> <i class="fa fa-bed"></i> <span>
                    Hotel CRS </span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <?php if (check_user_previlege('p144')) {
                ?>
                    <li><a href="<?php echo base_url() . 'index.php/hotel/hotel_crs_list' ?>">
                            <i class="fas fa-circle-notch"></i>Hotel List & Room Allocation</a></li>
                <?php
                }
                ?>

                <!--      <li class=""><a href="<?php echo base_url() . 'index.php/hotels/hotels_enquiry'; ?>">
                        <i class="fas fa-circle-notch"></i> Inquiry</a></li> -->

                <!--   <li class=""><a href="<?php echo base_url() . 'index.php/hotels/quotation_list'; ?>">
                        <i class="fas fa-circle-notch"></i> Quotation List</a></li>-->
                <?php if (check_user_previlege('p145')) {
                ?>

                    <li><a href="<?php echo base_url() . 'index.php/hotel/hotel_types' ?>"><i class="fas fa-circle-notch"></i>Hotel Type</a></li>
                <?php
                }
                ?>
                <?php if (check_user_previlege('p146')) {
                ?>
                    <li><a href="<?php echo base_url() . 'index.php/hotel/room_types' ?>"><i class="fas fa-circle-notch"></i></i>Room Type</a></li>
                <?php
                }
                ?>
                <?php if (check_user_previlege('p147')) {
                ?>
                    <li><a href="<?php echo base_url() . 'index.php/hotel/board_types' ?>"><i class="fas fa-circle-notch"></i></i>Board Type</a></li>
                <?php
                }
                ?>
                <?php if (check_user_previlege('p148')) {
                ?>
                    <li class=""><a href="<?php echo base_url() . 'index.php/hotel/hotel_ammenities' ?>"><i class="fas fa-circle-notch"></i> Hotel Amenities</a></li>
                <?php
                }
                ?>
                <?php if (check_user_previlege('p149')) {
                ?>
                    <li class=""><a href="<?php echo base_url() . 'index.php/hotel/room_ammenities' ?>"><i class="fas fa-circle-notch"></i> Room Amenities</a></li>
                <?php
                }
                ?>

                <?php if (check_user_previlege('p150')) {
                ?>
                    <li class=""><a href="<?php echo base_url() . 'index.php/hotels/room_meal_type' ?>"><i class="fa fa-database"></i> Room Meal Type</a></li>
                <?php
                }
                ?>



                <?php if (check_user_previlege('p151')) {
                ?>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-plus-square"></i>
                            <span>Nationality Management </span><i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if (check_user_previlege('p152')) {
                            ?>
                                <li>
                                    <a href="<?php echo base_url() . 'index.php/hotel/nationality_region' ?>"><i class="fa fa-circle-notch"></i>Region</a>
                                </li>
                            <?php
                            }
                            ?>
                            <?php if (check_user_previlege('p153')) {
                            ?>
                                <li>
                                    <a href="<?php echo base_url() . 'index.php/hotel/view_notionality_country' ?>"><i class="fa fa-circle-notch"></i>Country</a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </li>
                <?php
                }
                ?>
            </ul>
    </li>
<?php
        }
?>
<!--Flight CRS -->
<li class="treeview hide">
    <a href="#"> <i class="fa fa-plane"></i> <span>Flight CRS </span><i class="fa fa-angle-left pull-right"></i> </a>
    <ul class="treeview-menu">
        <?php //if(check_user_previlege('p37')):
        ?>
        <li><a href="<?php echo base_url() . 'index.php/flight_crs/add_flight' ?>"><i class="fa fa-credit-card"></i> <span>Add Flight</span></a></li>
        <?php //endif;
        ?>
        <?php //if(check_user_previlege('p38')):
        ?>
        <li><a href="<?php echo base_url() . 'index.php/flight_crs/flight_list' ?>"><i class="fa fa-credit-card"></i> <span>Flight List</span></a></li>
        <?php //endif;
        ?>
        <?php //if(check_user_previlege('p39')):
        ?>
        <li><a href="<?php echo base_url() . 'index.php/flight_crs/flight_fare_rules' ?>"><i class="fa fa-credit-card"></i> <span>Flight Fare Rules</span></a></li>
        <li><a href="<?php echo base_url() . 'index.php/flight_crs/flight_crs_airline_list' ?>"><i class="fa fa-credit-card"></i> <span>Airline list</span></a></li>
        <li><a href="<?php echo base_url() . 'index.php/flight_crs/flight_crs_airport_list' ?>"><i class="fa fa-credit-card"></i> <span>Airport list</span></a></li>
        <?php //endif;
        ?>
        <!-- <li><a href="<?php /* echo base_url().'index.php/flight/flight_meal_details' */ ?>"><i class="fa fa-credit-card"></i> <span>Flight Meal Details</span></a></li> -->
    </ul>
</li>
<!--END -->
<li class="treeview hide"><a href="#"> <i class="fa fa-plus-square"></i> <span>
            Private cars <span class="data-crs">1</span></span><i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <!-- USER TYPES -->
        <li><a href="<?php echo base_url() . 'index.php/privatecar/view_packages_types' ?>"><i class="fas fa-circle-notch"></i>Package Types </a></li>
        <li><a href="<?php echo base_url() . 'index.php/privatecar/view_category_types' ?>"><i class="fas fa-circle-notch"></i>Vehicle Category</a></li>
        <li><a href="<?php echo base_url() . 'index.php/privatecar/view_car_types' ?>"><i class="fas fa-circle-notch"></i>Vendor Master</a></li>
        <li><a href="<?php echo base_url() . 'index.php/privatecar/view_size_types' ?>"><i class="fas fa-circle-notch"></i>Vehicle sizes </a></li>
        <li><a href="<?php echo base_url() . 'index.php/privatecar/extra_services' ?>"><i class="fas fa-circle-notch"></i>Extra Service Master</a></li>
        <li><a href="<?php echo base_url() . 'index.php/privatecar/add_with_price' ?>"><i class="fas fa-circle-notch"></i>Add Vehicle</a></li>
        <li><a href="<?php echo base_url() . 'index.php/privatecar/view_with_price' ?>"><i class="fas fa-circle-notch"></i>View Vehicle</a></li>

    </ul>
</li>
<?php
if (check_user_previlege('p129')) {
?>
    <li class="treeview">
        <a href="#"> <i class="fa fa-car"></i> <span>
                Transfers Management </span><i class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <?php
            if (check_user_previlege('p131')) {
            ?>
                <li><a href="<?php echo base_url() . 'index.php/transfers/add_transfer' ?>"><i class="fa fa-circle-notch"></i>Add Transfer</a></li>

            <?php

            }
            ?>
            <?php
            if (check_user_previlege('p130')) {
            ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-plus-square"></i>
                        <span>Cancellation Management</span><i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <!-- USER TYPES -->
                        <li><a href="<?php echo base_url() . 'index.php/transfers/cancellation_policy' ?>"><i class="fa fa-circle-notch"></i>Cancellation Policy</a></li>
                    </ul>
                </li>
            <?php

            }
            ?>
            <?php
            if (check_user_previlege('p132')) {
            ?>
                <li><a href="<?php echo base_url() . 'index.php/transfers/view_transfer_list' ?>"><i class="fa fa-circle-notch"></i>Transfer List</a></li>
            <?php

            }
            ?>
            <?php
            if (check_user_previlege('p133')) {
            ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-plus-square"></i>
                        <span>Transfers Master Management</span><i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-plus-square"></i>
                                <span>Transfer Types</span><i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <!-- USER TYPES -->
                                <li><a href="<?php echo base_url() . 'index.php/transfers/view_packages_types' ?>"><i class="fas fa-circle-notch"></i> View Transfer Types </a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-plus-square"></i>
                                <span>Transfer Vehicle</span><i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <!-- USER TYPES -->
                                <li><a href="<?php echo base_url() . 'index.php/transfers/transfer_vehicle' ?>"><i class="fa fa-circle-notch"></i>Add Vehicle</a></li>
                                <li><a href="<?php echo base_url() . 'index.php/transfers/view_vehicle_list' ?>"><i class="fa fa-circle-notch"></i>List Vehicle</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-plus-square"></i>
                                <span>Transfer Driver</span><i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <!-- USER TYPES -->
                                <li><a href="<?php echo base_url() . 'index.php/transfers/transfer_driver' ?>"><i class="fa fa-circle-notch"></i>Add Driver</a></li>
                                <li><a href="<?php echo base_url() . 'index.php/transfers/view_driver_list' ?>"><i class="fa fa-circle-notch"></i>List Driver</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-plus-square"></i>
                                <span>Nationality Management </span><i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li>
                                    <a href="<?php echo base_url() . 'index.php/transfers/nationality_region' ?>"><i class="fa fa-circle-notch"></i>Region</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url() . 'index.php/transfers/view_notionality_country' ?>"><i class="fa fa-circle-notch"></i>Country</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>
            <?php

            }
            ?>
        </ul>
    </li>

<?php

}
?>
<?php
if (check_user_previlege('p13')) :

?>
    <li class="treeview">
        <a href="#">
            <i class="far fa-laptop"></i>
            <span>CMS</span><i class="far fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">

            <li><a href="<?php echo base_url() . 'index.php/cms/rescheduleflights' ?>"><i class="far fa-plane"></i><span>Reschedule Flight</span></a></li>
            <li><a href="<?php echo base_url() . 'index.php/cms/Cancellationtimeline' ?>"><i class="far fa-file-alt"></i><span>Cancellation timeline</span></a></li>
            <li><a href="<?php echo base_url() . 'index.php/cms/terms_conditions' ?>"><i class="far fa-image"></i> <span>Terms Conditions</span></a></li>
            <!-- <li><a href="<?php echo base_url() . 'index.php/cms/terms_conditions_supplier' ?>"><i class="far fa-image"></i> <span> Supplier Terms Conditions</span></a></li> -->
            <li><a href="<?php echo base_url() . 'index.php/cms/add_voucher_content' ?>"><i class="far fa-file-alt"></i> <span> Voucher Content</span></a></li>
            <?php if (check_user_previlege('p44')) : ?>
                <li><a href="<?php echo base_url() . 'index.php/user/banner_images' ?>"><i class="far fa-image"></i> <span>Main Banner Image</span></a></li>
            <?php endif;
            if (check_user_previlege('p45')) : ?>
                <li><a href="<?php echo base_url() . 'index.php/cms/add_cms_page' ?>"><i class="far fa-file-alt"></i> <span>Static Page content</span></a></li>

                <!-- Top Destinations START -->
                <?php endif;
            if ($airline_module) {
                if (check_user_previlege('p46')) : ?>
                    <li class=""><a href="<?php echo base_url() . 'index.php/cms/flight_top_destinations' ?>"><i class="far fa-plane"></i> <span>Flight Top Destinations</span></a></li>
                    <!-- <li class=""><a href="<?php echo base_url() . 'index.php/cms/flight_perfect_package' ?>"><i class="far fa-plane"></i> <span>Flight Perfect Packages</span></a></li> -->
            <?php endif;
            } ?>
            <?php if ($accomodation_module) {
                if (check_user_previlege('p47')) : ?>
                    <li class=""><a href="<?php echo base_url() . 'index.php/cms/activityv1_top_destinations' ?>"><i class="fas fa-bed"></i> <span>Activity Top Destinations</span></a></li>
                    <!-- <li class=""><a href="<?php echo base_url() . 'index.php/cms/hotel_perfect_packages' ?>"><i class="fas fa-bed"></i> <span>Hotel Perfect Packages</span></a></li> -->
            <?php endif;
            } ?>
            <?php if ($sightseen_module) {
                if (check_user_previlege('p47')) : ?>
                    <!--<li class=""><a href="<?php echo base_url() . 'index.php/cms/activity_top_destinations' ?>"><i class="fas fa-binoculars"></i> <span>Activity Top Destinations</span></a></li>-->
                    <!-- <li class=""><a href="<?php echo base_url() . 'index.php/cms/activity_perfect_packages' ?>"><i class="fas fa-binoculars"></i> <span>Activity Perfect Packages</span></a></li> -->
            <?php endif;
            } ?>

            <?php if ($sightseen_module) {
                if (check_user_previlege('p47')) : ?>
                    <li class=""><a href="<?php echo base_url() . 'index.php/cms/transfercms_top_destinations ' ?>"><i class="fas fa-taxi"></i> <span>Transfers Top Destinations</span></a></li>
                    <!-- <li class=""><a href="<?php echo base_url() . 'index.php/cms/transfers_perfect_packages ' ?>"><i class="fas fa-taxi"></i> <span>Transfers Perfect Packages</span></a></li> -->
            <?php endif;
            } ?>

            <!-- <?php if ($car_module) {
                        if (check_user_previlege('p47')) : ?>
                <li class=""><a href="<?php echo base_url() . 'index.php/cms/car_top_destinations ' ?>"><i class="fas fa-binoculars"></i> <span>Car Top Destinations(Home)</span></a></li>
            <?php endif;
                    } ?>
             <?php if ($car_module) {
                    if (check_user_previlege('p47')) : ?>
                <li class=""><a href="<?php echo base_url() . 'index.php/cms/car_inner_top_destinations ' ?>"><i class="fas fa-binoculars"></i> <span>Car Top Destinations(Inner)</span></a></li>
                <li class=""><a href="<?php echo base_url() . 'index.php/cms/car_perfect_packages ' ?>"><i class="fas fa-binoculars"></i> <span>Car Perfect Packages</span></a></li>
            <?php endif;
                } ?> -->

            <?php if ($sightseen_module) {
                if (check_user_previlege('p47')) : ?>
                    <!-- <li class=""><a href="<?php echo base_url() . 'index.php/cms/adv_banner ' ?>"><i class="fas fa-binoculars"></i> <span>Advertisement Banner</span></a></li> -->
            <?php endif;
            } ?>
            <?php if ($sightseen_module) {
                if (check_user_previlege('p47')) : ?>
                    <!-- <li class=""><a href="<?php echo base_url() . 'index.php/cms/subscribe_banner ' ?>"><i class="fas fa-binoculars"></i> <span>Subscribe Banner </span></a></li> -->
            <?php endif;
            } ?>
            <?php if ($sightseen_module) {
                if (check_user_previlege('p47')) : ?>
                    <li class=""><a href="<?php echo base_url() . 'index.php/cms/about_us ' ?>"><i class="fas fa-binoculars"></i> <span>About Us </span></a></li>
            <?php endif;
            } ?>


            <?php if ($bus_module) {
                if (check_user_previlege('p51')) : ?>
                    <!-- <li class=""><a href="<?php echo base_url() . 'index.php/cms/bus_top_destinations' ?>"><i class="far fa-bus"></i> <span>Bus Top Destinations</span></a></li> -->
                <?php endif;
            }
            if (check_user_previlege('p52')) : ?>
                <!--  <li class=""><a href="<?php echo base_url() . 'index.php/cms/home_page_headings' ?>"><i class="far fa-book"></i> <span>Home Page Headings</span></a></li> -->
            <?php endif;
            if (check_user_previlege('p53')) : ?>
                <!-- <li class=""><a href="<?php echo base_url() . 'index.php/cms/why_choose_us' ?>"><i class="far fa-question"></i> <span>Why Choose Us</span></a></li> -->
            <?php endif;
            if (check_user_previlege('p54')) : ?>
                <!-- <li class=""><a href="<?php echo base_url() . 'index.php/cms/top_airlines' ?>"><i class="far fa-plane"></i> <span>Top Airlines</span></a></li> -->
            <?php endif;
            if (check_user_previlege('p55')) : ?>
                <!-- <li class=""><a href="<?php echo base_url() . 'index.php/cms/tour_styles' ?>"><i class="far fa-binoculars"></i> <span>Tour Styles</span></a></li> -->
            <?php endif;
            if (check_user_previlege('p64')) : ?>
                <li class=""><a href="<?php echo base_url() . 'index.php/cms/add_contact_address' ?>"><i class="far fa-address-card"></i> <span>Contact Address</span></a></li>
            <?php endif; ?>
            <!-- <li><a href="<?php echo base_url() . 'index.php/user/affiliate_partners_images' ?>"><i class="far fa-image"></i> <span>Affiliates Partners</span></a></li>
            <li><a href="<?php echo base_url() . 'index.php/user/trusted_by_experts_images' ?>"><i class="far fa-image"></i> <span>Trusted by Experts</span></a></li>
            <li><a href="<?php echo base_url() . 'index.php/user/who_we_are_images' ?>"><i class="far fa-image"></i> <span>Who We Are</span></a></li>
            <li><a href="<?php echo base_url() . 'index.php/user/blog_images' ?>"><i class="far fa-image"></i> <span>Blog</span></a></li> -->
            <li><a href="<?php echo base_url() . 'index.php/user/testimonial_images' ?>"><i class="far fa-quote-left"></i> <span>Testimonials</span></a></li>
            <li><a href="<?php echo base_url() . 'index.php/cms/general_review/' ?>"><i class="fa fa-eye"></i> Reviews </a></li>
            <!-- <li><a href="<?php echo base_url() . 'index.php/cms/investment_chart' ?>"><i class="far fa-chart-pie"></i> <span>Investment Chart</span></a></li>
            <li><a href="<?php echo base_url() . 'index.php/user/gallery_url_images' ?>"><i class="far fa-image"></i> <span>Gallery</span></a></li>
            <li><a href="<?php echo base_url() . 'index.php/user/gallery_images' ?>"><i class="far fa-image"></i> <span>Gallery Images</span></a></li>
            <li><a href="<?php echo base_url() . 'index.php/cms/contact_us' ?>"><i class="far fa-users"></i> <span>Contact_Details_List</span></a></li> -->

            <!-- Top Destinations END -->
        </ul>
    </li>
<?php endif;
if (check_user_previlege('p12')) : ?>
    <li class="treeview">
        <a href="<?php echo base_url() . 'index.php/cms/seo' ?>">
            <i class="fa fa-university"></i>
            <span>SEO</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <!--<a href="<?php //echo base_url() . 'index.php/cms/seo_flight' 
                        ?>" >
            <i class="fa fa-university"></i>
            <span>SEO Flight</span> <i class="fa fa-angle-left pull-right"></i>
        </a>-->
        <!--<a href="<?php //echo base_url() . 'index.php/cms/seo_hotel' 
                        ?>" >
            <i class="fa fa-university"></i>
            <span>SEO Hotel</span> <i class="fa fa-angle-left pull-right"></i>
        </a>-->
        <!-- <a href="<?php //echo base_url() . 'index.php/cms/seo_transfers' 
                        ?>" >
            <i class="fa fa-university"></i>
            <span>SEO Transfers</span> <i class="fa fa-angle-left pull-right"></i>
        </a>-->
        <!-- <a href="<?php // echo base_url() . 'index.php/cms/seo_activity' 
                        ?>" >
            <i class="fa fa-university"></i>
            <span>SEO Activity</span> <i class="fa fa-angle-left pull-right"></i>
        </a>-->
        <!--  <a href="<?php //echo base_url() . 'index.php/cms/seo_holidays' 
                        ?>" >
            <i class="fa fa-university"></i>
            <span>SEO Holidays</span> <i class="fa fa-angle-left pull-right"></i>
        </a>-->
    </li>
<?php endif;
?>
<?php if (check_user_previlege('p139')) { ?>
    <li class="treeview"><a href="#"> <i class="fa fa-laptop"></i> <span>Supplier Management</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <?php if (check_user_previlege('p140')) { ?>
                <li><a href="<?php echo base_url() . 'index.php/supplier_management/report' ?>"><i class="fa fa-user" aria-hidden="true"></i> <span>Supplier Report</span></a></li>
            <?php

            }
            ?>
            <?php if (check_user_previlege('p141')) { ?>
                <li><a href="<?php echo base_url() . 'index.php/supplier_management/payment_details' ?>"><i class="fa fa-user" aria-hidden="true"></i> <span>Supplier Ledger</span></a></li>
            <?php

            }
            ?>
        </ul>
    </li>
<?php

}
if (@check_user_previlege('p1')) :
?>
    <li class="treeview">
        <a href="<?php echo base_url() . 'management/bank_account_details_supplier' ?>">
            <i class="fa fa-university"></i>
            <span>Supplier Bank Details</span> <i class="fa fa-angle-left pull-right"></i>
        </a>
    </li>
<?php endif;
if (check_user_previlege('p15')) : ?>
    <li class="treeview">
        <a href="<?php echo base_url() . 'index.php/management/bank_account_details' ?>">
            <i class="far fa-university"></i> <span>Bank Account Details</span> </a>
    </li>
<?php endif;
if (check_user_previlege('p15')) : ?>

    <li class="treeview">
        <a href="<?php echo base_url() . 'index.php/general/email_configuration' ?>">
            <i class="far fa-envelope"></i> <span>Email Configuration</span> </a>
    </li>
<?php endif; ?>
<!-- 
    <li class="treeview">
                    <a href="<?php //echo base_url().'index.php/utilities/deal_sheets' 
                                ?>">
                            <i class="far fa-hand-o-right "></i> <span>Deal Sheets</span>
                    </a>
    </li>
    -->
<?php if (check_user_previlege('p16')) : ?>
    <li class="treeview">
        <a href="#">
            <i class="far fa-cogs"></i>
            <span> Settings </span><i class="far fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <?php if (check_user_previlege('p59')) : ?>
                <li>
                    <a href="<?php echo base_url() . 'index.php/utilities/convenience_fees' ?>"><i class="far fa-credit-card"></i>Convenience Fees</a>
                </li>
            <?php endif;
            if (check_user_previlege('p60')) : ?>

                <li>
                    <a href="<?php echo base_url() . 'index.php/utilities/manage_promo_code' ?>"><i class="far fa-tag"></i>Promo Code</a>
                </li>
            <?php endif;
            if (check_user_previlege('p61')) : ?>

                <li class="hide">
                    <a href="<?php echo base_url() . 'index.php/utilities/manage_source' ?>"><i class="far fa-database"></i> Manage API</a>
                </li>
            <?php endif;
            if (check_user_previlege('p61')) : ?>


            <?php endif;
            if (is_domain_user() == false) { // ACCESS TO ONLY PROVAB ADMIN  
            ?>
                <li>
                    <a href="<?php echo base_url() . 'index.php/utilities/module' ?>"><i class="far fa-circle"></i> <span>Manage Modules</span>
                    </a>
                </li>
            <?php }
            if (check_user_previlege('p62')) : ?>

                <li>
                    <a href="<?php echo base_url() . 'index.php/utilities/currency_converter' ?>"><i class="fas fa-rupee-sign"></i> Currency Conversion </a>
                </li>
            <?php endif;
            if (check_user_previlege('p65')) : ?>
                <li>
                    <a href="<?php echo base_url() . 'index.php/management/event_logs' ?>"><i class="far fa-shield"></i> <span> Event Logs </span></a>
                </li>
            <?php endif;
            if (check_user_previlege('p66')) : ?>
                <!--  <li>
                <a href="<?php echo base_url() . 'index.php/utilities/app_settings' ?>"><i class="far fa-laptop"></i> Appearance </a>
            </li> -->
            <?php endif;
            if (check_user_previlege('p67')) : ?>
                <li>
                    <a href="<?php echo base_url() . 'index.php/utilities/social_network' ?>"><i class="fab fa-facebook-square"></i> Social Networks </a>
                </li>
            <?php endif;
            if (check_user_previlege('p68')) : ?>
                <li>
                    <a href="<?php echo base_url() . 'index.php/utilities/social_login' ?>"><i class="fab fa-facebook-f"></i> Social Login </a>
                </li>
            <?php endif;
            if (check_user_previlege('p69')) : ?>
                <li>
                    <a href="<?php echo base_url() . 'index.php/user/manage_domain' ?>">
                        <i class="far fa-image"></i> <span>Manage Domain</span>
                    </a>
                </li>
            <?php endif;
            if (check_user_previlege('p70')) : ?>
                <li>
                    <a href="<?php echo base_url() ?>index.php/utilities/timeline"><i class="far fa-desktop"></i> <span>Live Events</span></a>
                </li>
            <?php endif; ?>
            <!-- <li>
                    <a href="<?= base_url() . 'index.php/utilities/trip_calendar' ?>"><i class="far fa-calendar"></i> <span>Trip Calendar</span></a>
</li> -->
        </ul>
    </li>
<?php endif; ?>

</ul>