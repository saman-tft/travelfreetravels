<!-- <style>
    #modal-container {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 99999999999999999999999999999999999999999;
        justify-content: center;
        align-items: center;
    }



    .insurance-modal .close-button {
        padding: 5px 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: -15px;
        right: -20px;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 50%;
        z-index: 999999999999999999999999999999999999999999999;
        cursor: pointer;
    }

    .insurance-modal {
        position: relative;
        display: block;
        font-family: "Roboto", sans-serif;
        min-width: 40%;
        min-height: 90%;
        max-width: 40%;
        max-height: auto;
        text-align: center;
        background: white;
        opacity: 0;
        transition: all 400ms ease;
    }

    .insurance-modal.active {
        opacity: 1;

    }


    .loading__insurance {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .plans__button-container{
        display:none;
        justify-content:space-between;
        align-items:flex-start;
        padding:2em 5em;
    }

    @media (max-width: 280px) {
        .insurance-modal {
            max-width: 80vw;
        }


    }

    @media only screen and (min-width: 281px) and (max-width: 480px) {
        .insurance-modal {
            max-width: 85vw;
        }


    }

    @media only screen and (min-width: 480px) and (max-width: 768px) {
        .insurance-modal {
            max-width: 85vw;
        }

        .insurance-modal {
            max-width: 85vw;
        }


    }

    @media only screen and (min-width: 768px) and (max-width: 834px) {
        .insurance-modal {
            max-width: 85vw;
        }


    }
</style>
<div id="modal-container">
    <div class="insurance-modal active">
        <div class="close-button">
            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="12" viewBox="0 0 384 512">
                <path fill="#ffffff" d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
            </svg>
        </div>
        <div class="plans__button-container">
            <button onclick="showFamilyPlans()">Family Plans</button>
            <button onclick="showPerPassengerPlans()">Individual Plans</button>
        </div>
        <div class="loading__insurance">
            <img src="<?php echo SYSTEM_IMAGE_DIR . "loading.gif" ?>" alt="Loading..." />
            <p>Getting available insurance plan for your trip...</p>
        </div>
        <div class="insurance__list">

        </div>

    </div>
</div> -->
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    #loading {
        display: block;
        margin: 0 auto;
    }
</style>
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="modal-body">
            <!-- Modal content will be inserted here -->
        </div>
    </div>
</div>

<?php
$total_seg_cnt = 0;
foreach ($pre_booking_summery['SegmentDetails'] as $segk => $segv) {
    $total_seg_cnt += count($segv);
}
$discount_details = discount_details();
$discount = 0;
if ($discount_details['status'] == SUCCESS_STATUS) {
    $discount = $total_seg_cnt * $discount_details['data']['value'];
}
include_once 'process_tbo_response.php';
$template_images = $GLOBALS['CI']->template->template_images();
$FareDetails = $pre_booking_summery['FareDetails']['b2b_PriceDetails'];
$PassengerFareBreakdown = $pre_booking_summery['PassengerFareBreakdown'];
$SegmentDetails = $pre_booking_summery['SegmentDetails'];
$SegmentSummary = $pre_booking_summery['SegmentSummary'];
$hold_ticket = $pre_booking_summery['HoldTicket'];
//Total Fare
$flight_total_amount = $FareDetails['_CustomerBuying'];
$currency_symbol = $FareDetails['CurrencySymbol'];
//Segment Details
$flight_segment_details = flight_segment_details($SegmentDetails, $SegmentSummary);

$is_domestic = $pre_booking_params['is_domestic'];
if ($is_domestic != true) {
    $pass_mand = '<sup class="text-danger">*</sup>';
    $pass_req = 'required="required"';
} else {
    $pass_mand = '';
    $pass_req = '';
}
$mandatory_filed_marker = '<sup class="text-danger">*</sup>';
//Balu A
$is_domestic_flight = $search_data['is_domestic_flight'];
if ($is_domestic_flight) {
    $temp_passport_expiry_date = date('Y-m-d', strtotime('+5 years'));
    $static_passport_details = array();
    $static_passport_details['passenger_passport_expiry_day'] = date('d', strtotime($temp_passport_expiry_date));
    $static_passport_details['passenger_passport_expiry_month'] = date('m', strtotime($temp_passport_expiry_date));
    $static_passport_details['passenger_passport_expiry_year'] = date('Y', strtotime($temp_passport_expiry_date));
}
if (is_logged_in_user()) {
    $review_active_class = ' success ';
    $review_tab_details_class = '';
    $review_tab_class = ' inactive_review_tab_marker ';
    $travellers_active_class = ' active ';
    $travellers_tab_details_class = ' gohel ';
    $travellers_tab_class = ' travellers_tab_marker ';
} else {
    $review_active_class = ' active ';
    $review_tab_details_class = ' gohel ';
    $review_tab_class = ' review_tab_marker ';
    $travellers_active_class = '';
    $travellers_tab_details_class = '';
    $travellers_tab_class = ' inactive_travellers_tab_marker ';
}
// changes start agent booking page and sidebar: changed user country code to +977 for default Nepalese
// $user_country_code = '+971';
$user_country_code = '+977';
// changes end agent booking page and sidebar: changed user country code to +977 for default Nepalese
echo generate_low_balance_popup($FareDetails['_CustomerBuying'] + $FareDetails['_GST']);
?>
<style>
    .topssec::after {
        display: none;
    }

    .farehd {
        margin: 0 15px 15px 15px !important;
    }

    /* CSS to center the modal */
    .modal-dialog {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .modal-content {
        text-align: center;
    }
</style>
<div class="fldealsec">
    <div class="container">
        <!-- Bootstrap modal for loading -->
        <div class="modal" id="loadingModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <p class="loadingtext">Loading....</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="tabcontnue">
            <div class="col-xs-4 nopadding">
                <div class="rondsts <?= $review_active_class ?>">
                    <a class="taba core_review_tab <?= $review_tab_class ?>" id="stepbk1">
                        <div class="iconstatus fa fa-eye"></div>
                        <div class="stausline">Review</div>
                    </a>
                </div>
            </div>
            <div class="col-xs-4 nopadding">
                <div class="rondsts <?= $travellers_active_class ?>">
                    <a class="taba core_travellers_tab <?= $travellers_tab_class ?>" id="stepbk2">
                        <div class="iconstatus fa fa-group"></div>
                        <div class="stausline">Travellers</div>
                    </a>
                </div>
            </div>
            <div class="col-xs-4 nopadding">
                <div class="rondsts">
                    <a class="taba" id="stepbk3">
                        <div class="iconstatus fa fa-money"></div>
                        <div class="stausline">Payments</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="alldownsectn">
    <div class="container">
        <?php if ($is_price_Changed == true) { ?>
            <div class="farehd arimobold">
                <span class="text-danger">* Price has been changed from supplier end</span>
            </div>
        <?php } ?>
        <div class="ovrgo">
            <div class="bktab1 xlbox <?= $review_tab_details_class ?>">
                <div class="col-xs-8 nopadding full_summery_tab">
                    <div class="fligthsdets">
                        <div class="flitab1">
                            <!-- Segment Details Starts-->
                            <div class="moreflt boksectn">
                                <?php echo $flight_segment_details['segment_full_details']; ?>
                            </div>
                            <!-- Segment Details Ends-->
                            <div class="clearfix"></div>
                            <div class="sepertr"></div>
                            <!--
                                    <div class="promocode">
                                            <div class="col-xs-6">
                                            <div class="mailsign">Have a discount / promo code to redeem</div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="tablesign">
                                              <div class="inputsign">
                                                <input type="text" placeholder="Enter Coupon" class="newslterinput nputbrd">
                                              </div>
                                              <div class="submitsign">
                                                <button class="promobtn">Apply</button>
                                              </div>
                                           </div>
                                        </div>
                                    </div>
                            -->
                            <div class="sepertr"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bktab2 xlbox <?= $travellers_tab_details_class ?> flight_booking_desc">
                <div class="topalldesc">
                    <div class="col-xs-8 nopadding celtbcel segment_seg">
                        <?php //echo flight_segment_abstract_details($pre_booking_params);
                        echo $flight_segment_details['segment_abstract_details'];
                        ?>
                    </div>
                    <!-- Outer Summary -->
                    <div class="col-xs-4 nopadding celtbcel colrcelo">
                        <div class="bokkpricesml">
                            <div class="travlrs">Travellers: <span class="fa fa-male"></span> <?php echo $search_data['adult']; ?> | <span class="fa fa-child"></span> <?php echo $search_data['child']; ?> | <span class="infantbay"><img src="<?= $template_images ?>infant.png" alt="" /></span> <?php echo $search_data['infant']; ?></div>
                            <div class="totlbkamnt"> Total Amount <?php echo $currency_symbol; ?> <span id="total_booking_amount"><?php echo round($flight_total_amount - $discount); ?></span></div>
                            <a class="fligthdets" data-toggle="collapse" data-target="#fligtdetails">Flight Details</a>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <!-- Segment Details Starts-->
                <div class="collapse splbukdets" id="fligtdetails">
                    <div class="moreflt insideagain">
                        <?php echo $flight_segment_details['segment_full_details']; ?>
                    </div>
                </div>
                <!-- Segment Details Ends-->
                <div class="clearfix"></div>
                <div class="padpaspotr">
                    <div class="col-xs-8 nopadding tab_pasnger">
                        <div class="fligthsdets">
                            <?php
                            /**
                             * Collection field name 
                             */
                            //Title, Firstname, Middlename, Lastname, Phoneno, Email, PaxType, LeadPassenger, Age, PassportNo, PassportIssueDate, PassportExpDate
                            $total_adult_count = is_array($search_data['adult_config']) ? array_sum($search_data['adult_config']) : intval($search_data['adult_config']);
                            $total_child_count = is_array($search_data['child_config']) ? array_sum($search_data['child_config']) : intval($search_data['child_config']);
                            $total_infant_count = is_array($search_data['infant_config']) ? array_sum($search_data['infant_config']) : intval($search_data['infant_config']);
                            //------------------------------ DATEPICKER START
                            $i = 1;
                            $datepicker_list = array();
                            if ($total_adult_count > 0) {
                                for ($i = 1; $i <= $total_adult_count; $i++) {
                                    $datepicker_list[] = array('adult-date-picker-' . $i, ADULT_DATE_PICKER);
                                }
                            }

                            if ($total_child_count > 0) {
                                //id should be auto picked so initialize $i to previous value of $i
                                for ($i = $i; $i <= ($total_child_count + $total_adult_count); $i++) {
                                    $datepicker_list[] = array('child-date-picker-' . $i, CHILD_DATE_PICKER);
                                }
                            }

                            if ($total_infant_count > 0) {
                                //id should be auto picked so initialize $i to previous value of $i
                                for ($i = $i; $i <= ($total_child_count + $total_adult_count + $total_infant_count); $i++) {
                                    $datepicker_list[] = array('infant-date-picker-' . $i, INFANT_DATE_PICKER);
                                }
                            }

                            $GLOBALS['CI']->current_page->set_datepicker($datepicker_list);
                            //------------------------------ DATEPICKER END

                            $total_pax_count = $total_adult_count + $total_child_count + $total_infant_count;
                            //First Adult is Primary and and Lead Pax
                            $adult_enum = $child_enum = get_enum_list('title');
                            $gender_enum = get_enum_list('gender');
                            unset($adult_enum[MASTER_TITLE]); // Master is for child so not required
                            unset($child_enum[MASTER_TITLE]); // Master is not supported in TBO list
                            $adult_title_options = generate_options($adult_enum, false, true);
                            $child_title_options = generate_options($child_enum, false, true);

                            $gender_options = generate_options($gender_enum);
                            $nationality_options = generate_options($iso_country_list, array(INDIA_CODE)); //FIXME get ISO CODE --- ISO_INDIA
                            $passport_issuing_country_options = generate_options($country_list);

                            if ($search_data['trip_type'] == 'oneway') {
                                $passport_minimum_expiry_date = date('Y-m-d', strtotime($search_data['depature']));
                            } else if ($search_data['trip_type'] == 'circle') {
                                //debug($search_data);exit;
                                $passport_minimum_expiry_date = date('Y-m-d', strtotime($search_data['return']));
                            } else {
                                $passport_minimum_expiry_date = date('Y-m-d', strtotime(end($search_data['depature'])));
                            }
                            //$passport_minimum_expiry_date = date('Y-m-d', strtotime('2018-01-01'));
                            //lowest year wanted
                            $cutoff = date('Y', strtotime('+20 years', strtotime($passport_minimum_expiry_date)));
                            //current year
                            //$now = date('Y');
                            $now = date('Y', strtotime($passport_minimum_expiry_date));
                            $day_options = generate_options(get_day_numbers());
                            $month_options = generate_options(get_month_names());
                            $year_options = generate_options(get_years($now, $cutoff));

                            //params for citizenship
                            $startCitizenship = date('Y', strtotime('-200 years', strtotime($passport_minimum_expiry_date)));
                            $citizenshipYears = array_reverse(get_years($startCitizenship, $now));
                            // debug($citizenshipYears);die;
                            $year_options_citizenship = generate_options($citizenshipYears);

                            /**
                             * check if current print index is of adult or child by taking adult and total pax count
                             * @param number $total_pax     total pax count
                             * @param number $total_adult   total adult count
                             */
                            function pax_type($pax_index, $total_adult, $total_child, $total_infant)
                            {
                                if ($pax_index <= $total_adult) {
                                    $pax_type = 'adult';
                                } elseif ($pax_index <= ($total_adult + $total_child)) {
                                    $pax_type = 'child';
                                } else {
                                    $pax_type = 'infant';
                                }
                                return $pax_type;
                            }

                            /**
                             * check if current print index is of adult or child by taking adult and total pax count
                             * @param number $total_pax     total pax count
                             * @param number $total_adult   total adult count
                             */
                            function is_adult($pax_index, $total_adult)
                            {
                                return ($pax_index > $total_adult ? false : true);
                            }

                            function pax_type_count($pax_index, $total_adult, $total_child, $total_infant)
                            {
                                if ($pax_index <= $total_adult) {
                                    $pax_count = ($pax_index);
                                } elseif ($pax_index <= ($total_adult + $total_child)) {
                                    $pax_count = ($pax_index - $total_adult);
                                } else {
                                    $pax_count = ($pax_index - ($total_adult + $total_child));
                                }
                                return $pax_count;
                            }

                            /**
                             * check if current print index is of adult or child by taking adult and total pax count
                             * @param number $total_pax     total pax count
                             * @param number $total_adult   total adult count
                             */
                            function is_lead_pax($pax_count)
                            {
                                return ($pax_count == 1 ? true : false);
                            }
                            ?>
                            <form action="<?= base_url() . 'index.php/flight/pre_booking/' . $search_data['search_id'] ?>" method="POST" autocomplete="off" id="pre-booking-form">
                                <div class="hide">
                                    <input type="hidden" required="required" name="search_id" value="<?= $search_data['search_id']; ?>" />
                                    <?php $dynamic_params_url = serialized_data($pre_booking_params); ?>
                                    <input type="hidden" required="required" name="token" value="<?= $dynamic_params_url; ?>" />
                                    <input type="hidden" required="required" name="token_key" value="<?= md5($dynamic_params_url); ?>" />
                                    <input type="hidden" required="required" name="op" value="book_room">
                                    <input type="hidden" required="required" name="booking_source" value="<?= $booking_source ?>" readonly>
                                    <input type="hidden" required="required" name="segdis" value="<?= $discount ?>" readonly>
                                    <input type="text" name="redeem_points_post" class="redeem_points_post" value="0">
                                    <input type="hidden" name="reward_usable" value="<?= round($reward_usable) ?>">
                                    <input type="hidden" name="reward_earned" value="<?= round($reward_earned) ?>">
                                    <input type="hidden" id="selectedPlanInput" name="selectedPlan">
                                    <input type="hidden" id="selectedPlanIdInput" name="selectedPlanId">
                                    <input type="hidden" id="selectedPlansJson" name="selectedPlansJson">

                                    <input type="hidden" name="total_price_with_rewards" value="<?= round($total_price_with_rewards) ?>">
                                    <input type="hidden" name="reducing_amount" class="reduceamount" value="<?= round($reducing_amount) ?>" <!--<input type="hidden" required="required" name="provab_auth_key" value="?=$ProvabAuthKey ?>" readonly>
                                    -->
                                </div>
                                <div class="flitab1">
                                    <div class="moreflt boksectn">
                                        <div class="ontyp">
                                            <div class="labltowr arimobold">Please enter names as on passport. </div>
                                            <?php
                                            $pax_index = 1;

                                            $lead_pax_details = @$pax_details[0];

                                            if (is_logged_in_user()) {
                                                //Can Enable this for B2B
                                                //$traveller_class = ' user_traveller_details ';
                                                $traveller_class = '';
                                            } else {
                                                $traveller_class = '';
                                            }
                                            for ($pax_index = 1; $pax_index <= $total_pax_count; $pax_index++) { //START FOR LOOP FOR PAX DETAILS
                                                $cur_pax_info = is_array($pax_details) ? array_shift($pax_details) : array();
                                                $pax_type = pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count);
                                                $pax_type_count = pax_type_count($pax_index, $total_adult_count, $total_child_count, $total_infant_count);

                                                if ($pax_type != 'infant') {
                                                    $extract_pax_name_cls = ' extract_pax_name_cls ';
                                                } else {
                                                    $extract_pax_name_cls = '';
                                                }
                                            ?>
                                                <div class="pasngr_input pasngrinput _passenger_hiiden_inputs">
                                                    <div class="hide hidden_pax_details">
                                                        <input type="hidden" name="passenger_type[]" value="<?= ucfirst($pax_type) ?>">
                                                        <input type="hidden" name="lead_passenger[]" value="<?= (is_lead_pax($pax_index) ? true : false) ?>">
                                                        <input type="hidden" name="gender[]" value="1" class="pax_gender">
                                                        <input type="hidden" required="required" name="passenger_nationality[]" id="passenger-nationality-<?= $pax_index ?>" value="<?php echo $nationality_code; ?>">>
                                                    </div>
                                                    <div class="col-xs-1 nopadding full_dets_aps">
                                                        <div class="adltnom"><?= ucfirst($pax_type) ?><?= $pax_type_count ?><?= $mandatory_filed_marker ?></div>
                                                    </div>
                                                    <div class="col-xs-11 nopadding full_dets_aps">
                                                        <div class="inptalbox">
                                                            <div class="col-xs-3 spllty">
                                                                <div class="selectedwrap">
                                                                    <select class="mySelectBoxClass flyinputsnor name_title" name="name_title[]" required="required">
                                                                        <?php echo (is_adult($pax_index, $total_adult_count) ? $adult_title_options : $child_title_options) ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-5 spllty">
                                                                <!-- <input value="<?= @$cur_pax_info['first_name'] ?>" required="required" type="text" name="first_name[]" id="passenger-first-name-<?= $pax_index ?>" class="<?= $extract_pax_name_cls ?> clainput alpha_space <?= $traveller_class ?>" maxlength="45" placeholder="Enter First Name" data-row-id="<?= ($pax_index); ?>"/> -->
                                                                <input value="" required="required" type="text" name="first_name[]" id="passenger-first-name-<?= $pax_index ?>" class="<?= $extract_pax_name_cls ?>  clainput <?= $traveller_class ?>" maxlength="45" placeholder="Enter First Name" data-row-id="<?= ($pax_index); ?>" />
                                                            </div>
                                                            <div class="col-xs-4 spllty">
                                                                <!-- <input value="<?= @$cur_pax_info['last_name'] ?>" required="required" type="text" name="last_name[]" id="passenger-last-name-<?= $pax_index ?>" class="<?= $extract_pax_name_cls ?> clainput alpha_space" maxlength="45" placeholder="Enter Last Name" /> -->
                                                                <input value="" required="required" type="text" name="last_name[]" id="passenger-last-name-<?= $pax_index ?>" class="<?= $extract_pax_name_cls ?> clainput " maxlength="45" placeholder="Enter Last Name" />
                                                            </div>
                                                            <?php if ($pax_type == 'infant') { //Only For Infant 
                                                            ?>
                                                                <div class="col-xs-6 spllty infant_dob_div">
                                                                    <div class="col-xs-4 nopadding"><span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span></div>
                                                                    <div class="col-xs-8 nopadding">
                                                                        <input placeholder="DOB" type="text" class="clainput" name="date_of_birth[]" readonly="readonly" <?= (is_adult($pax_index, $total_adult_count) ? 'required="required"' : 'required="required"') ?> id="<?= strtolower(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count)) ?>-date-picker-<?= $pax_index ?>">
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            } else { //Adult/Child
                                                                if (($pax_type == 'adult' && $is_domestic_flight == false)) {  ?>
                                                                    <div class="col-xs-6 spllty infant_dob_div">
                                                                        <div class="col-xs-4 nopadding"><span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span></div>
                                                                        <div class="col-xs-8 nopadding">
                                                                            <input placeholder="DOB" type="text" class="clainput" name="date_of_birth[]" readonly <?= (is_adult($pax_index, $total_adult_count) ? 'required="required"' : 'required="required"') ?> id="<?= strtolower(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count)) ?>-date-picker-<?= $pax_index ?>">
                                                                        </div>
                                                                    </div>
                                                                <?php } else if (($pax_type == 'child')) { ?>
                                                                    <div class="col-xs-6 spllty infant_dob_div">
                                                                        <div class="col-xs-4 nopadding"><span class="fmlbl">Date of Birth <?= $mandatory_filed_marker ?></span></div>
                                                                        <div class="col-xs-8 nopadding">
                                                                            <input placeholder="DOB" type="text" class="clainput" name="date_of_birth[]" readonly <?= (is_adult($pax_index, $total_adult_count) ? 'required="required"' : 'required="required"') ?> id="<?= strtolower(pax_type($pax_index, $total_adult_count, $total_child_count, $total_infant_count)) ?>-date-picker-<?= $pax_index ?>">
                                                                        </div>
                                                                    </div>
                                                            <?php }
                                                            } ?>
                                                            <!-- <div class="adult_child_dob_div hide">
                                                            <input type="hidden" name="date_of_birth[]" value="<?= $static_date_of_birth ?>">
                                                        </div> -->

                                                            <div class="clearfix"></div>
                                                            <!-- Passport Section Starts -->
                                                            <div class="passport_content_div">
                                                                <?php if ($is_domestic_flight == false) { //For Internatinal Travel 
                                                                ?>
                                                                    <!--<input placeholder="DOB" type="hidden" class="clainput" name="date_of_birth[]" value="<?php echo $addob ?>">-->

                                                                    <div class="passport_content_div">
                                                                        <?php if ($is_domestic_flight == false) {
                                                                            $addob = date('Y-m-d', strtotime('-30 years')); //For Internatinal Travel  
                                                                        ?>
                                                                            <!--<input placeholder="DOB" type="hidden" class="clainput" name="date_of_birth[]" value="<?php echo $addob ?>">-->
                                                                            <script>
                                                                                $(document).ready(function() {
                                                                                    var isNepInd = <?= $isNepInd ? 'true' : 'false' ?>;
                                                                                    var $identificationType = $('#identification_type_<?= $pax_index ?>');

                                                                                    if (isNepInd) {
                                                                                        $('.international_citizenship_content_div_<?= $pax_index ?>').hide();
                                                                                        $('.international_passport_content_div_<?= $pax_index ?>').show();
                                                                                        $identificationType.val('passport'); // Set select option to "passport"
                                                                                    } else {
                                                                                        $('.international_citizenship_content_div_<?= $pax_index ?>').hide();
                                                                                        $('.international_passport_content_div_<?= $pax_index ?>').show();
                                                                                        $identificationType.val('passport'); // Set select option to "passport"
                                                                                    }
                                                                                    0
                                                                                    $identificationType.on('change', function() {
                                                                                        var selectedValue = $(this).val();
                                                                                        if (selectedValue === 'citizenship') {
                                                                                            $('.international_passport_content_div_<?= $pax_index ?>').hide();
                                                                                            $('.international_citizenship_content_div_<?= $pax_index ?>').show();
                                                                                        } else if (selectedValue === 'passport') {
                                                                                            $('.international_citizenship_content_div_<?= $pax_index ?>').hide();
                                                                                            $('.international_passport_content_div_<?= $pax_index ?>').show();
                                                                                        }
                                                                                    });

                                                                                    $('#flip').on('click', function(e) {
                                                                                        var selectedValue = $identificationType.val();
                                                                                        var formFilled = true;

                                                                                        if (selectedValue === 'citizenship') {
                                                                                            $('.international_citizenship_content_div_<?= $pax_index ?> input').each(function() {
                                                                                                if ($(this).val() === '') {
                                                                                                    formFilled = false;
                                                                                                    $(this).siblings('.error-msg').text('This field is required.');
                                                                                                } else {
                                                                                                    $(this).siblings('.error-msg').text('');
                                                                                                }
                                                                                            });
                                                                                        } else if (selectedValue === 'passport') {
                                                                                            $('.international_passport_content_div_<?= $pax_index ?> input').each(function() {
                                                                                                if ($(this).val() === '') {
                                                                                                    formFilled = false;
                                                                                                    $(this).siblings('.error-msg').text('This field is required.');
                                                                                                } else {
                                                                                                    $(this).siblings('.error-msg').text('');
                                                                                                }
                                                                                            });
                                                                                        }

                                                                                        if (!formFilled) {
                                                                                            e.preventDefault();
                                                                                        }
                                                                                    });
                                                                                });
                                                                            </script>
                                                                            <div class="col-xs-12_<?= $pax_index ?>">
                                                                                <label>Please select a form of identification</label>
                                                                                <select name="identification_type[]" id="identification_type_<?= $pax_index ?>" placeholder="Select a form of identification" class="form-control">
                                                                                    <?php if ($isNepInd) : ?>
                                                                                        <option value="citizenship">Citizenship</option>
                                                                                        <option value="passport">Passport</option>
                                                                                    <?php else : ?>
                                                                                        <option value="passport">Passport</option>
                                                                                    <?php endif; ?>
                                                                                </select>
                                                                            </div>
                                                                            <?php if ($isNepInd) : ?>
                                                                                <div class="international_passport_content_div_<?= $pax_index ?>">
                                                                                    <div class="col-xs-4 spllty">
                                                                                        <div id="passport">
                                                                                            <span class="formlabel">Passport Number <?= $pass_mand ?></span>
                                                                                            <div class="relativemask">
                                                                                                <input type="text" name="passenger_passport_number[]" <?= $pass_req ?> id="passenger_passport_number_<?= $pax_index ?>" class="clainput" maxlength="10" placeholder="Passport Number" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xs-3 spllty">
                                                                                        <span class="formlabel">Issuing Country <?= $pass_mand ?></span>
                                                                                        <div class="selectedwrap">
                                                                                            <select name="passenger_passport_issuing_country[]" <?= $pass_req ?> id="passenger_passport_issuing_country_<?= $pax_index ?>" class="mySelectBoxClass flyinputsnor">
                                                                                                <option value="INVALIDIP">Please Select</option>
                                                                                                <?= $passport_issuing_country_options ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-xs-5 spllty">
                                                                                        <span class="formlabel">Date of Expire <?= $pass_mand ?></span>
                                                                                        <!-- Changed layout of this div to fix tab error previour:(dd/mm/yyyy)-> now (yyyy/mm/dd) -->
                                                                                        <div class="relativemask">
                                                                                            <div class="col-xs-4 splinmar">
                                                                                                <div class="selectedwrap">
                                                                                                    <select name="passenger_passport_expiry_year[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_year" data-expiry-type="year" id="passenger_passport_expiry_year_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                                        <option value="INVALIDIP">YYYY
                                                                                                        </option>
                                                                                                        <?= $year_options; ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-xs-4 splinmar">
                                                                                                <div class="selectedwrap">
                                                                                                    <select name="passenger_passport_expiry_month[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_month" data-expiry-type="month" id="passenger_passport_expiry_month_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                                        <option value="INVALIDIP">MM
                                                                                                        </option>
                                                                                                        <?= $month_options; ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-xs-4 splinmar">
                                                                                                <div class="selectedwrap">
                                                                                                    <select name="passenger_passport_expiry_day[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_day" data-expiry-type="day" id="passenger_passport_expiry_day_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                                        <option value="INVALIDIP">DD
                                                                                                        </option>
                                                                                                        <?= $day_options; ?>
                                                                                                    </select>
                                                                                                    <input type="hidden" value="<?php echo $now; ?>" id="travel_year">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="pull-right text-danger hide" id="passport_error_msg_<?= $pax_index ?>"></div>
                                                                                </div>
                                                                                <div class="international_citizenship_content_div_<?= $pax_index ?>">
                                                                                    <div class="col-xs-4 spllty">
                                                                                        <div id="citizenship">
                                                                                            <span class="formlabel">citizenship Number <?= $pass_mand ?></span>
                                                                                            <div class="relativemask">
                                                                                                <input type="text" name="passenger_citizenship_number[]" <?= $pass_req ?> id="passenger_citizenship_number_<?= $pax_index ?>" class="clainput" maxlength="10" placeholder="citizenship Number" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xs-3 spllty">
                                                                                        <span class="formlabel">Issuing Country <?= $pass_mand ?></span>
                                                                                        <div class="selectedwrap">
                                                                                            <select name="passenger_citizenship_issuing_country[]" <?= $pass_req ?> id="passenger_citizenship_issuing_country_<?= $pax_index ?>" class="mySelectBoxClass flyinputsnor">
                                                                                                <option value="INVALIDIP">Please Select</option>
                                                                                                <?= $passport_issuing_country_options ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-xs-5 spllty">
                                                                                        <span class="formlabel">Date of Issue <?= $pass_mand ?></span>
                                                                                        <!-- Changed layout of this div to fix tab error previour:(dd/mm/yyyy)-> now (yyyy/mm/dd) -->
                                                                                        <div class="relativemask">
                                                                                            <div class="col-xs-4 splinmar">
                                                                                                <div class="selectedwrap">
                                                                                                    <select name="passenger_citizenship_expiry_year[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor citizenship_expiry_year" data-expiry-type="year" id="passenger_citizenship_expiry_year_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                                        <option value="INVALIDIP">YYYY
                                                                                                        </option>
                                                                                                        <?= $year_options_citizenship; ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-xs-4 splinmar">
                                                                                                <div class="selectedwrap">
                                                                                                    <select name="passenger_citizenship_expiry_month[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor citizenship_expiry_month" data-expiry-type="month" id="passenger_citizenship_expiry_month_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                                        <option value="INVALIDIP">MM
                                                                                                        </option>
                                                                                                        <?= $month_options; ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-xs-4 splinmar">
                                                                                                <div class="selectedwrap">
                                                                                                    <select name="passenger_citizenship_expiry_day[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor citizenship_expiry_day" data-expiry-type="day" id="passenger_citizenship_expiry_day_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                                        <option value="INVALIDIP">DD
                                                                                                        </option>
                                                                                                        <?= $day_options; ?>
                                                                                                    </select>
                                                                                                    <input type="hidden" value="<?php echo $now; ?>" id="travel_year">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="pull-right text-danger hide" id="citizenship_error_msg_<?= $pax_index ?>"></div>
                                                                                </div>
                                                                            <?php else : ?>
                                                                                <div class="international_passport_content_div_<?= $pax_index ?>">
                                                                                    <div class="col-xs-4 spllty">
                                                                                        <div id="passport">
                                                                                            <span class="formlabel">Passport Number <?= $pass_mand ?></span>
                                                                                            <div class="relativemask">
                                                                                                <input type="text" name="passenger_passport_number[]" <?= $pass_req ?> id="passenger_passport_number_<?= $pax_index ?>" class="clainput" maxlength="10" placeholder="Passport Number" />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-xs-3 spllty">
                                                                                        <span class="formlabel">Issuing Country <?= $pass_mand ?></span>
                                                                                        <div class="selectedwrap">
                                                                                            <select name="passenger_passport_issuing_country[]" <?= $pass_req ?> id="passenger_passport_issuing_country_<?= $pax_index ?>" class="mySelectBoxClass flyinputsnor">
                                                                                                <option value="INVALIDIP">Please Select</option>
                                                                                                <?= $passport_issuing_country_options ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-xs-5 spllty">
                                                                                        <span class="formlabel">Date of Expire <?= $pass_mand ?></span>
                                                                                        <!-- Changed layout of this div to fix tab error previour:(dd/mm/yyyy)-> now (yyyy/mm/dd) -->
                                                                                        <div class="relativemask">
                                                                                            <div class="col-xs-4 splinmar">
                                                                                                <div class="selectedwrap">
                                                                                                    <select name="passenger_passport_expiry_year[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_year" data-expiry-type="year" id="passenger_passport_expiry_year_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                                        <option value="INVALIDIP">YYYY
                                                                                                        </option>
                                                                                                        <?= $year_options; ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-xs-4 splinmar">
                                                                                                <div class="selectedwrap">
                                                                                                    <select name="passenger_passport_expiry_month[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_month" data-expiry-type="month" id="passenger_passport_expiry_month_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                                        <option value="INVALIDIP">MM
                                                                                                        </option>
                                                                                                        <?= $month_options; ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-xs-4 splinmar">
                                                                                                <div class="selectedwrap">
                                                                                                    <select name="passenger_passport_expiry_day[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_day" data-expiry-type="day" id="passenger_passport_expiry_day_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                                        <option value="INVALIDIP">DD
                                                                                                        </option>
                                                                                                        <?= $day_options; ?>
                                                                                                    </select>
                                                                                                    <input type="hidden" value="<?php echo $now; ?>" id="travel_year">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="pull-right text-danger hide" id="passport_error_msg_<?= $pax_index ?>"></div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php
                                                                        } else { //For Domestic Travel, Set Static Passport Data
                                                                            $passport_number = rand(1111111111, 9999999999);
                                                                            $passport_issuing_country = 92;
                                                                            $citizenship_number = '';
                                                                            $citizenship_issuing_country = 140;
                                                                        ?>
                                                                            <div class="domestic_passport_content_div hide">
                                                                                <input type="hidden" name="passenger_passport_number[]" value="<?= $passport_number ?>" id="passenger_passport_number_<?= $pax_index ?>">
                                                                                <input type="hidden" name="passenger_passport_issuing_country[]" value="<?= $passport_issuing_country ?>" id="passenger_passport_issuing_country_<?= $pax_index ?>">
                                                                                <input type="hidden" name="passenger_passport_expiry_day[]" value="<?= $static_passport_details['passenger_passport_expiry_day'] ?>" id="passenger_passport_expiry_day_<?= $pax_index ?>">
                                                                                <input type="hidden" name="passenger_passport_expiry_month[]" value="<?= $static_passport_details['passenger_passport_expiry_month'] ?>" id="passenger_passport_expiry_month_<?= $pax_index ?>">
                                                                                <input type="hidden" name="passenger_passport_expiry_year[]" value="<?= $static_passport_details['passenger_passport_expiry_year'] ?>" id="passenger_passport_expiry_year_<?= $pax_index ?>">
                                                                            </div>
                                                                            <div class="domestic_citizenship_content_div hide">
                                                                                <input type="hidden" name="passenger_citizenship_number[]" value="<?= $citizenship_number ?>" id="passenger_citizenship_number_<?= $pax_index ?>">
                                                                                <input type="hidden" name="passenger_citizenship_issuing_country[]" value="<?= $citizenship_issuing_country ?>" id="passenger_citizenship_issuing_country_<?= $pax_index ?>">
                                                                                <input type="hidden" name="passenger_citizenship_expiry_day[]" value="<?= $static_citizenship_details['passenger_citizenship_expiry_day'] ?>" id="passenger_citizenship_expiry_day_<?= $pax_index ?>">
                                                                                <input type="hidden" name="passenger_citizenship_expiry_month[]" value="<?= $static_citizenship_details['passenger_citizenship_expiry_month'] ?>" id="passenger_citizenship_expiry_month_<?= $pax_index ?>">
                                                                                <input type="hidden" name="passenger_citizenship_expiry_year[]" value="<?= $static_citizenship_details['passenger_citizenship_expiry_year'] ?>" id="passenger_passport_expiry_year_<?= $pax_index ?>">
                                                                            </div>
                                                                        <?php } ?>
                                                                    </div>
                                                                    <!-- Passport Section Ends-->



                                                                    <!-- <div class="col-xs-3 spllty">
                                                                        <span class="formlabel">Nationality <?= $pass_mand ?></span>
                                                                        <div class="selectedwrap">
                                                                            <select name="nationality[]" <?= $pass_req ?> id="passenger_passport_issuing_country_<?= $pax_index ?>" class="mySelectBoxClass flyinputsnor">
                                                                                <option value="INVALIDIP">Please Select</option>
                                                                                <?= $passport_issuing_country_options ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
 -->





                                                                    <!--  <div class="col-xs-5 spllty">
                                                                        <span class="formlabel">Date of Expire <?= $pass_mand ?></span>
                                                                        <div class="relativemask">
                                                                            <div class="col-xs-4 splinmar">
                                                                                <div class="selectedwrap">
                                                                                    <select name="passenger_passport_expiry_day[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_day" data-expiry-type="day" id="passenger_passport_expiry_day_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                        <option value="INVALIDIP">DD</option>
                                                                                        <?= $day_options; ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xs-4 splinmar">
                                                                                <div class="selectedwrap">
                                                                                    <select name="passenger_passport_expiry_month[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_month" data-expiry-type="month" id="passenger_passport_expiry_month_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                        <option value="INVALIDIP">MM</option>
                                                                                        <?= $month_options; ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-xs-4 splinmar">
                                                                                <div class="selectedwrap">
                                                                                    <select name="passenger_passport_expiry_year[]" <?= $pass_req ?> class="mySelectBoxClass flyinputsnor passport_expiry_year" data-expiry-type="year" id="passenger_passport_expiry_year_<?= $pax_index ?>" data-row-id="<?= ($pax_index); ?>">
                                                                                        <option value="INVALIDIP">YYYY</option>
                                                                                        <?= $year_options; ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="pull-right text-danger hide" id="passport_error_msg_<?= $pax_index ?>"></div>
                                                            </div>
                                                        <?php
                                                                } else { //For Domestic Travel, Set Static Passport Data
                                                                    //$passport_number = rand(1111111111, 9999999999);
                                                                    //$passport_issuing_country = 92;
                                                        ?> -->
                                                                    <div class="domestic_passport_content_div hide">
                                                                        <input type="hidden" name="passenger_passport_number[]" value="<?= $passport_number ?>" id="passenger_passport_number_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_issuing_country[]" value="<?= $passport_issuing_country ?>" id="passenger_passport_issuing_country_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_expiry_day[]" value="<?= $static_passport_details['passenger_passport_expiry_day'] ?>" id="passenger_passport_expiry_day_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_expiry_month[]" value="<?= $static_passport_details['passenger_passport_expiry_month'] ?>" id="passenger_passport_expiry_month_<?= $pax_index ?>">
                                                                        <input type="hidden" name="passenger_passport_expiry_year[]" value="<?= $static_passport_details['passenger_passport_expiry_year'] ?>" id="passenger_passport_expiry_year_<?= $pax_index ?>">
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <!-- Passport Section Ends-->
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } //END FOR LOOP FOR PAX DETAILS
                                            ?>
                                        </div>
                                    </div>

                                    <div class="sepertr"></div>
                                    <div class="clearfix"></div>
                                    <div class="contbk">
                                        <div class="contcthdngs">CONTACT DETAILS</div>
                                        <div class="col-xs-12 nopad full_smal_forty">
                                            <div class="col-xs-12 nopad mb10 full_smal_forty">
                                                <div class="col-xs-5 nopadding">
                                                    <div class="hide">
                                                        <input type="hidden" name="billing_country" value="92">
                                                        <input type="hidden" name="billing_city" value="test">
                                                        <input type="hidden" name="billing_zipcode" value="test">
                                                    </div>
                                                    <select class="newslterinput nputbrd _numeric_only ">
                                                        <?php echo diaplay_phonecode($phone_code, $active_data, $user_country_code); ?>
                                                    </select>
                                                </div>
                                                <div class="col-xs-1">
                                                    <div class="sidepo">-</div>
                                                </div>
                                                <div class="col-xs-6 nopadding">
                                                    <!-- <input value="<?= @$lead_pax_details['phone'] == 0 ? '' : @$lead_pax_details['phone']; ?>" type="text" name="passenger_contact" id="passenger-contact" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only" maxlength="10" required="required"> -->
                                                    <input value="" type="text" name="passenger_contact" id="passenger-contact" placeholder="Mobile Number" class="newslterinput nputbrd _numeric_only" required="required">
                                                </div>
                                            </div>

                                            <div class="emailperson col-xs-12 nopad full_smal_forty">
                                                <!-- <input value="<?= @$lead_pax_details['email'] ?>" type="text" maxlength="80" required="required" id="billing-email" class="newslterinput nputbrd" placeholder="Email" name="billing_email"> -->
                                                <input value="" type="text" maxlength="80" required="required" id="billing-email" class="newslterinput nputbrd" placeholder="Email" name="billing_email">
                                            </div>

                                            <div class="clearfix"></div>
                                            <!--changes start agent booking page and sidebar: commented code block-->
                                            <!--<div class="emailperson col-xs-12 nopad full_smal_forty">-->
                                            <!-- <textarea rows="3" name="billing_address_1" class="newsltertextarea nputbrd" placeholder="Address" required="required"><= @$agent_address ?></textarea>
                                                 -->
                                            <!--<textarea rows="3" name="billing_address_1" class="newsltertextarea nputbrd" placeholder="Address" required="required"></textarea>-->
                                            <!--</div>-->
                                            <!--changes end agent booking page and sidebar: commented code block-->
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="notese">Mobile number & Email ID will be used only for sending flight related communication.</div>
                                    </div>
                                    <!--changes start agent booking page and sidebar: commented code block-->
                                    <!-- Insurance section -->
                                    <?php
                                    $requestData = array();
                                    $requestData['segmentDetails'] = $SegmentDetails;
                                    $requestData['search_id'] = $search_data['search_id'];
                                    $segmentDetails = base64_encode(json_encode($SegmentDetails, true));
                                    $searchId = $search_data['search_id'];
                                    ?>
                                    <div id="insuranceSection">
                                        <p>Would you like to purchase insurance for this flight?</p>
                                        <a href='<?php echo base_url() . "insurance/GetAvailablePlansOTAWithRiders/" . $search_data['search_id'] . '/' . $segmentDetails ?>'>Insure Trip</a>
                                        <a href='<?php echo base_url() . "insurance/index" ?>'>Confirm Insurance</a>
                                        <button type="button" onclick="showModal()" class="btn btn-primary" id="yesBtn">Yes</button>
                                        <button type="button" class="btn btn-danger" id="noBtn">No</button>
                                    </div>
                                    <div id="errorDiv"></div>

                                    <!-- Insurance plans section -->
                                    <div id="insurancePlans" style="display: none;">
                                        <!-- Placeholder for plans -->
                                    </div>
                                    <!--<div class="panel-group" role="tablist" aria-multiselectable="true">-->
                                    <!--<div class="panel panel-default for_gst flight_special_req">-->
                                    <!--    <div class="panel-heading" role="tab" id="gst_opt">-->
                                    <!--        <h4 class="panel-title">-->
                                    <!--            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#gst_optnl" aria-expanded="true" aria-controls="gst_optnl">-->

                                    <!--                <div class="labltowr arimobold">GST Information(Optional) <i class="more-less glyphicon glyphicon-plus"></i></div>-->
                                    <!--            </a>-->
                                    <!--        </h4>-->
                                    <!--    </div>-->
                                    <!--    <div id="gst_optnl" class="panel-collapse collapse" role="tabpanel" aria-labelledby="gst_opt">-->
                                    <!-- <div class="contcthdngs">GST Information(Optional)</div> -->
                                    <!--    <div class="col-xs-12 gst_det" id="gst_form_div">-->
                                    <!--       <div class="row">-->
                                    <!--          <div class="col-xs-3"> GST Number </div>-->
                                    <!--          <div class="col-xs-7"> -->
                                    <!--             <input type="text" class="newslterinput clainput nputbrd" id="gst_number" name="gst_number" value="">    -->
                                    <!--             <div class="clearfix"></div>-->
                                    <!--             <div class="gst_number_error alert-danger hide" style="width:250px;">Please Enter Valid GST Number</div>-->
                                    <!--          </div>-->
                                    <!--       </div>-->
                                    <!--       <div class="row">-->
                                    <!--          <div class="col-xs-3"> GST company Name </div>-->
                                    <!--          <div class="col-xs-7"> -->
                                    <!--             <input type="text" class="newslterinput nputbrd" id="gst_company_name" name="gst_company_name" vaule="">  -->
                                    <!--              <div class="clearfix"></div>-->
                                    <!--             <div class="gst_name_error alert-danger hide" style="width:250px;">Please Enter Valid Company Name</div>  -->
                                    <!--          </div>-->
                                    <!--       </div>-->
                                    <!--       <div class="row">-->
                                    <!--          <div class="col-xs-3"> Email </div>-->
                                    <!--          <div class="col-xs-7"> -->
                                    <!--             <input type="email" class="newslterinput nputbrd" id="gst_email" name="gst_email" value="">  -->
                                    <!--    <div class="clearfix"></div>-->
                                    <!--    <div class="gst_email_error alert-danger hide" style="width:250px;">Please Enter Valid Email</div>    -->
                                    <!--          </div>-->
                                    <!--       </div>                                          -->
                                    <!--       <div class="row">-->
                                    <!--          <div class="col-xs-3"> Phone Number </div>-->
                                    <!--          <div class="col-xs-7"> -->
                                    <!--             <input type="text" class="newslterinput nputbrd _numeric_only" id="gst_phone" name="gst_phone"  value="">    -->
                                    <!--     <div class="clearfix"></div>-->
                                    <!--    <div class="gst_phone_error alert-danger hide" style="width:250px;">Please Enter Valid Phone Number</div>        -->
                                    <!--          </div>-->
                                    <!--       </div>-->
                                    <!--       <div class="row">-->
                                    <!--          <div class="col-xs-3"> Address </div>-->
                                    <!--          <div class="col-xs-7"> -->
                                    <!--             <input type="text" class="newslterinput nputbrd" name="gst_address" id="gst_address" value="">    -->
                                    <!--              <div class="clearfix"></div>-->
                                    <!--    <div class="gst_address_error alert-danger hide" style="width:250px;">Please Enter Valid Address</div>-->
                                    <!--          </div>-->
                                    <!--       </div>-->
                                    <!--       <div class="row">-->
                                    <!--          <div class="col-xs-3"> State </div>-->
                                    <!--          <div class="col-xs-7">-->
                                    <!--          <php $state_list = generate_options($state_list);?>-->
                                    <!--          <select name="gst_state" class="clainput" id="gst_state">-->
                                    <!--                <option value="INVALIDIP">Please Select</option>-->
                                    <!--                <=$state_list?>-->
                                    <!--            </select>-->
                                    <!--            <div class="clearfix"></div>-->
                                    <!--    <div class="gst_state_error alert-danger hide" style="width:250px;">Please Enter Valid State</div>-->
                                    <!--          </div>-->
                                    <!--       </div>-->
                                    <!--    </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--changes end agent booking page and sidebar: commented code block-->
                                    <div class="clikdiv">
                                        <div class="squaredThree">
                                            <input id="terms_cond1" type="checkbox" name="tc" checked="checked" required="required">
                                            <label for="terms_cond1"></label>
                                        </div>
                                        <span class="clikagre" id="clikagre">
                                            <!--changes start agent booking page and sidebar: added <a> tag with url for terms and conditions-->
                                            <a href="<?php echo DOMAIN_LINK_URL; ?>/terms">Terms and Conditions</a>
                                            <!--changes end agent booking page and sidebar: added <a> tag with url for terms and conditions-->
                                        </span>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="sepertr"></div>
                                    <div class="clearfix"></div>
                                    <!-- Dyanamic Baggage&Meals Section Starts -->
                                    <?php
                                    if (valid_array($extra_services) == true) {
                                        if (isset($extra_services['ExtraServiceDetails']['Baggage'])) {
                                            $baggage_meal_seat_details['baggage_meal_details']['Baggage'] = $extra_services['ExtraServiceDetails']['Baggage'];
                                        }
                                        if (isset($extra_services['ExtraServiceDetails']['Meals'])) {
                                            $baggage_meal_seat_details['baggage_meal_details']['Meals'] = $extra_services['ExtraServiceDetails']['Meals'];
                                        }
                                        if (isset($extra_services['ExtraServiceDetails']['Seat'])) {
                                            $baggage_meal_seat_details['baggage_meal_details']['Seat'] = $extra_services['ExtraServiceDetails']['Seat'];
                                        }
                                        $baggage_meal_seat_details['total_adult_count'] = $total_adult_count;
                                        $baggage_meal_seat_details['total_child_count'] = $total_child_count;
                                        $baggage_meal_seat_details['total_infant_count'] = $total_infant_count;
                                        $baggage_meal_seat_details['total_pax_count'] = $total_pax_count;
                                        echo $GLOBALS['CI']->template->isolated_view('flight/dynamic_baggage_meal_seat_details', $baggage_meal_seat_details);
                                    }
                                    ?>
                                    <!-- Dyanamic Baggage&Meals Section Ends -->
                                    <!-- Seats&Meals Preference Section Starts -->
                                    <?php
                                    if (valid_array($extra_services) == true) {
                                        if (isset($extra_services['ExtraServiceDetails']['MealPreference'])) {
                                            $seat_meal_preference_details['seat_meal_preference_details']['MealPreference'] = $extra_services['ExtraServiceDetails']['MealPreference'];
                                        }
                                        if (isset($extra_services['ExtraServiceDetails']['SeatPreference'])) {
                                            $seat_meal_preference_details['seat_meal_preference_details']['SeatPreference'] = $extra_services['ExtraServiceDetails']['SeatPreference'];
                                        }
                                        $seat_meal_preference_details['total_adult_count'] = $total_adult_count;
                                        $seat_meal_preference_details['total_child_count'] = $total_child_count;
                                        $seat_meal_preference_details['total_infant_count'] = $total_infant_count;
                                        $seat_meal_preference_details['total_pax_count'] = $total_pax_count;
                                        echo $GLOBALS['CI']->template->isolated_view('flight/seat_meal_preference_details', $seat_meal_preference_details);
                                    }
                                    ?>
                                    <!-- Seats&Meals Preference Section Ends -->
                                    <div class="clearfix"></div>
                                    <div class="loginspld">
                                        <div class="collogg">
                                            <?php
                                            //If single payment option then hide selection and select by default
                                            if (count($active_payment_options) == 1) {
                                                $payment_option_visibility = 'hide';
                                                $default_payment_option = 'checked="checked"';
                                            } else {
                                                $payment_option_visibility = 'show';
                                                $default_payment_option = '';
                                            }
                                            ?>
                                            <div class="row <?= $payment_option_visibility ?>">
                                                <?php if (in_array(PAY_NOW, $active_payment_options)) { ?>
                                                    <!--changes start agent booking page and sidebar: added: class form-inline and styles for class form-group, style for input field, moved input field above label-->
                                                    <!--<div class="col-md-3">-->
                                                    <!--    <div class="form-group">-->
                                                    <!--        <label for="payment-mode-<= PAY_NOW ?>">-->
                                                    <!--            <input <= $default_payment_option ?> name="payment_method" type="radio" required="required" value="<= PAY_NOW ?>" id="payment-mode-<= PAY_NOW ?>" class="form-control b-r-0" placeholder="Payment Mode">-->
                                                    <!--            Pay Now-->
                                                    <!--        </label>-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                    <div class="col-md-3">
                                                        <div class="form-group form-inline" style="font-size: large; color: black;">
                                                            <input <?= $default_payment_option ?> name="payment_method" type="radio" required="required" value="<?= PAY_NOW ?>" id="payment-mode-<?= PAY_NOW ?>" class="form-control b-r-0" style="width:15px; height:15px!important; scale:1.2; border-color:black;" placeholder="Payment Mode">
                                                            <label for="payment-mode-<?= PAY_NOW ?>">
                                                                Pay Now
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <!--changes end agent booking page and sidebar: added: class form-inline and styles for class form-group, style for input field, moved input field above label-->
                                                <?php } ?>
                                                <!--changes start agent booking page and sidebar: commented code block-->
                                                <!--<php if (in_array(PAY_AT_BANK, $active_payment_options)) { ?>-->
                                                <!--                                                    <div class="col-md-3">-->
                                                <!--                                                        <div class="form-group">-->
                                                <!--                                                            <label for="payment-mode-<= PAY_AT_BANK ?>">-->
                                                <!--                                                                <input <= $default_payment_option ?> name="payment_method" type="radio" required="required" value="<= PAY_AT_BANK ?>" id="payment-mode-<= PAY_AT_BANK ?>" class="form-control b-r-0" placeholder="Payment Mode">-->
                                                <!--                                                                Pay At Bank-->
                                                <!--                                                            </label>-->
                                                <!--                                                        </div>-->
                                                <!--                                                    </div>-->
                                                <!--                                            <php } ?>-->
                                                <!--changes end agent booking page and sidebar: commented code block-->
                                            </div>
                                            <input type="hidden" name="ticket_method" value="" id="ticket_method" />
                                            <?php if ($hold_ticket == true) { ?>
                                                <div class="continye col-sm-3 col-xs-6">
                                                    <button type="button" id="" name="flight" value="direct_ticket" class="continue_booking_button ticket_type_cls bookcont">Direct Ticket</button>
                                                </div>
                                                <div class="continye col-sm-3 col-xs-6">
                                                    <button type="submit" id="" name="flight" value="hold_ticket" class="continue_booking_button ticket_type_cls book_hold_ticket">Hold Ticket</button>
                                                </div>
                                            <?php } else {
                                            ?>
                                                <div class="continye col-sm-3 col-xs-6">
                                                    <button type="submit" id="" name="flight" class="bookcont continue_booking_button">Continue</button>
                                                </div>

                                            <?php } ?>

                                            <div class="clearfix"></div>
                                            <div class="sepertr"></div>
                                            <div class="temsandcndtn">
                                                Most countries require travellers to have a passport valid for more than 3 to 6 months from the date of entry into or exit from the country. Please check the exact rules for your destination country before completing the booking.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-xs-4 nopadding rit_summery">
                        <!--changes start agent booking page and sidebar: replaced variable $reward_usable with false in this condition-->
                        <?php if (false) { ?>
                            <!--changes end agent booking page and sidebar: replaced variable $reward_usable with false in this condition-->
                            <div>
                                <div class="col-xs-6 ">Redeem rewards

                                    <label class="switch"> <input type="checkbox" id="redeem_points" checked data-toggle="toggle" data-size="mini" name="redeem_points"> <span class="slider_rew
round"></span> </label>
                                </div>
                                <div class="col-xs-6 "><span id="booking_amount"><?= round($reward_usable) . " Points" ?></span></div> <?php } ?>
                            <!--changes start agent booking page and sidebar: replaced variable $reward_earned with false in this condition-->
                            <?php if (false) { ?>
                                <!--changes end agent booking page and sidebar: replaced variable $reward_earned with false in this condition-->
                                <div class="col-xs-6 ">Earning reward</div>
                                <div class="col-xs-6 "><span class="label label-primary"><?= $reward_earned . " Points" ?></span></div>
                            <?php } ?>
                            </div>
                            <?php echo get_fare_summary($FareDetails, $PassengerFareBreakdown, $discount); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<span class="hide">
    <input type="hidden" id="pri_passport_min_exp" value="<?= $passport_minimum_expiry_date ?>">
</span>

<?php echo $GLOBALS['CI']->template->isolated_view('share/flight_session_expiry_popup'); ?>
<?php echo $GLOBALS['CI']->template->isolated_view('share/passenger_confirm_popup'); ?>
<?php
/*
 * Balu A
 * Flight segment details
 * Outer summary and Inner Summary
 */

function flight_segment_details($SegmentDetails, $SegmentSummary)
{
    $loc_dir_icon = '<span class="fadr fa fa-long-arrow-right textcntr"></span>';
    $inner_summary = $outer_summary = '';
    //Inner Summary
    foreach ($SegmentDetails as $__segment_k => $__segment_v) {
        $segment_summary = $SegmentSummary[$__segment_k];
        //Calculate Total Duration of Onward/Return Journey
        $inner_summary .= '<div class="ontyp">';
        //Way Summary in one line - Start
        $inner_summary .= '<div class="labltowr arimobold">';
        $inner_summary .= $segment_summary['OriginDetails']['CityName'] . ' to ' . $segment_summary['DestinationDetails']['CityName'] . '<strong>(' . $segment_summary['TotalDuaration'] . ')</strong>';
        $inner_summary .= '</div>';
        //Way Summary in one line - End
        foreach ($__segment_v as $__stop => $__segment_flight) {
            //Summary of Way - Start
            $inner_summary .= '<div class="allboxflt">';
            //airline
            $inner_summary .= '<div class="col-xs-3 nopadding width_adjst">
                                    <div class="jetimg">
                                    <img  alt="' . $__segment_flight['AirlineDetails']['AirlineCode'] . '" src="' . SYSTEM_IMAGE_DIR . 'airline_logo/' . $__segment_flight['AirlineDetails']['AirlineCode'] . '.gif" >
                                    </div>
                                    <div class="alldiscrpo">
                                    ' . $__segment_flight['AirlineDetails']['AirlineName'] . '
                                    <span class="sgsmal">' . $__segment_flight['AirlineDetails']['AirlineCode'] . ' 
                                    <br />' . $__segment_flight['AirlineDetails']['FlightNumber'] . '</span>
                                    </div>
                                  </div>';
            //depart
            $inner_summary .= '<div class="col-xs-7 nopadding width_adjst">';
            $inner_summary .= '<div class="col-xs-5">
                                    <span class="airlblxl">' . month_date_time($__segment_flight['OriginDetails']['DateTime']) . '</span>
                                    <span class="portnme">' . $__segment_flight['OriginDetails']['AirportName'] . '</span>
                                    </div>';
            //direction indicator
            $inner_summary .= '<div class="col-xs-2">
                ' . $loc_dir_icon . '</div>';
            //arrival
            $inner_summary .= '<div class="col-xs-5">
                                    <span class="airlblxl">' . month_date_time($__segment_flight['DestinationDetails']['DateTime']) . '</span>
                                    <span class="portnme">' . $__segment_flight['DestinationDetails']['AirportName'] . '</span>
                                    </div>';
            $inner_summary .= '</div>';

            //Between Content -----
            $inner_summary .= '<div class="col-xs-2 nopadding width_adjst">
                                <span class="portnme textcntr">' . $__segment_flight['SegmentDuration'] . '</span>
                                <span class="portnme textcntr">Stop : ' . ($__stop) . '</span>
                                </div>';
            //Summary of Way - End
            $inner_summary .= '</div>';
            if (isset($__segment_v['WaitingTime']) == true) {
                $next_seg_info = $seg_v[$seg_details_k + 1];
                $waiting_time = $__segment_v['WaitingTime'];
                $inner_summary .= '
            <div class="clearfix"></div>
            <div class="connectnflt">
                <div class="conctncentr">
                <span class="fa fa-plane"></span>Change of planes at ' . $next_seg_info['OriginDetails']['AirportName'] . ' | <span class="fa fa-clock-o"></span> Waiting : ' . $waiting_time . '
            </div>
            </div>
            <div class="clearfix"></div>';
            }
        }
        $inner_summary .= '</div>';
    }
    //Outer Summry
    $total_stop_count = 0;
    $outer_summary .= '<div class="moreflt spltopbk">';
    foreach ($SegmentSummary as $__segment_k => $__segment_v) {
        $total_segment_travel_duration = $__segment_v['TotalDuaration'];
        $__stop_count = $__segment_v['TotalStops'];
        $total_stop_count += $__stop_count;
        $outer_summary .= '<div class="ontypsec">
                        <div class="allboxflt">';
        //airline
        $outer_summary .= '<div class="col-xs-3 nopadding width_adjst">
                            <div class="jetimg">
                            <img class="airline-logo" alt="' . $__segment_v['AirlineDetails']['AirlineCode'] . '" src="' . SYSTEM_IMAGE_DIR . 'airline_logo/' . $__segment_v['AirlineDetails']['AirlineCode'] . '.gif">
                            </div>
                            <div class="alldiscrpo">
                                    ' . $__segment_v['AirlineDetails']['AirlineName'] . '
                                    <span class="sgsmal"> ' . $__segment_v['AirlineDetails']['AirlineCode'] . '<br />' . $__segment_v['AirlineDetails']['FlightNumber'] . '</span>
                            </div>
                          </div>';
        $outer_summary .= '<div class="col-xs-7 nopadding width_adjst">';
        //depart
        $outer_summary .= '<div class="col-xs-5">
                                    <span class="airlblxl">' . $__segment_v['OriginDetails']['AirportName'] . '</span>
                                    <span class="portnme">' . month_date_time($__segment_v['OriginDetails']['DateTime']) . '</span>
                            </div>';
        //direction indicator
        $outer_summary .= '<div class="col-xs-2"><span class="fadr fa fa-long-arrow-right textcntr"></span></div>';
        //arrival
        $outer_summary .= '<div class="col-xs-5">
                                    <span class="airlblxl">' . $__segment_v['DestinationDetails']['AirportName'] . '</span>
                                    <span class="portnme">' . month_date_time($__segment_v['DestinationDetails']['DateTime']) . '</span>
                                </div>
                                </div>';
        //Stops/Class details
        $outer_summary .= '<div class="col-xs-2 nopadding width_adjst">
                                <span class="portnme textcntr">' . ($total_segment_travel_duration) . '</span>
                                <span class="portnme textcntr" >Stop:' . ($__stop_count) . '</span>
                        </div>';
        $outer_summary .= '</div></div>';
    }
    $outer_summary .= '</div>';
    return array('segment_abstract_details' => $outer_summary, 'segment_full_details' => $inner_summary);
}

function get_fare_summary($FareDetails, $PassengerFareBreakdown, $discount = 0)
{
    // debug($FareDetails);exit;
    $total_payable = $FareDetails['_TotalPayable'] + $FareDetails['_GST'] - $discount;
    $total_published_fare = $FareDetails['_CustomerBuying'] - $discount;
    $currency_symbol = $FareDetails['CurrencySymbol'];
    $gst_data = '';
    $gst_data1 = '';
    if ($FareDetails['_GST'] > 0) {
        $gst_data = '<div class="col-xs-8 nopadding">
                        <div class="faresty">GST</div>
                        </div>
                    <div class="col-xs-4 nopadding">
                        <div class="amnter arimobold">' . $currency_symbol . ' ' . $FareDetails['_GST'] . ' </div>
                    </div>
                    ';
        $gst_data1 = '<div class="reptallt">
                                        <div class="col-xs-8 nopadding">
                                            <div class="faresty">GST</div>
                                        </div>

                                        <div class="col-xs-4 nopadding">
                                            <div class="amnter arimobold">+' . $FareDetails['_GST'] . ' </div>
                                        </div>
                                        </div>';
    }
    $fare_summary = '<div class="insiefare">
                    <div class="farehd arimobold">Fare Summary</div>
                    <div class="fredivs">';
    $hide_show_fare_details = '<div class="kindrest">
                                            <a class="freshd show_details btn btn-sm pull-left" id="hide_show_net_fare" data-toggle="collapse" href="#net_fare_details" aria-expanded="false" aria-controls="net_fare_details">
                                            +SNF
                                            </a>
                                            </div>';
    $pax_base_fare_details = '<div class="kindrest">
                                <div class="freshd">Base Fare</div>';
    $pax_base_fare_details .= '<div class="reptallt">
                                <div class="col-xs-8 nopadding">
                                    <div class="faresty">Base Fare</div>
                                </div>
                                <div class="col-xs-4 nopadding">
                                    <div class="amnter arimobold">' . $currency_symbol . ' ' . $FareDetails['_BaseFare'] . ' </div>
                                </div>
                            </div>';
    $pax_tax_details = '<div class="kindrest">
                                    <div class="freshd">Taxes</div>';
    $pax_tax_details .= '<div class="reptallt">
                                <div class="col-xs-8 nopadding">
                                    <div class="faresty">Taxes & Fees</div>
                                </div>
                                <div class="col-xs-4 nopadding">
                                    <div class="amnter arimobold">' . $currency_symbol . ' ' . round($FareDetails['_TaxSum'] - $FareDetails['_GST']) . ' </div>
                                </div>

                                <div class="col-xs-8 nopadding">
                                    <div class="faresty">Discount(-)</div>
                                </div>
                                <div class="col-xs-4 nopadding">
                                 <div class="amnter arimobold discountorg " hidden>' . $discount . ' </div>
                                    <div class="amnter arimobold">' . $currency_symbol . '<span class="discount">' . $discount . '</span></div>
                                </div>

                            </div>' . $gst_data;
    $pax_base_fare_details .= '</div>';
    $pax_tax_details .= '</div>';

    $extar_service_charge_details = '<div class="">';
    $extar_service_charge_details .= '<div class="baggagecharge-agent" id="extra_baggage_charge_label" style="display:none">
                                                        <div class="col-xs-8 nopadding">Extra Baggage Charge</div>
                                                        <div class="col-xs-4 nopadding text-right">' . $currency_symbol . ' 
                                                            <span class="amnter arimobold" id="extra_baggage_charge"></span>
                                                            <span class="btn btn-sm btn-default" id="remove_extra_baggage"><i class="fa fa-times" aria-hidden="true"></i></span>
                                                            </div>
                                                    </div>
                                                    <div class="baggagecharge-agent" id="extra_meal_charge_label" style="display:none">
                                                        <div class="col-xs-8 nopadding">Meal Charge</div>
                                                            <div class="col-xs-4 nopadding text-right">' . $currency_symbol . '
                                                            <span class="amnter arimobold" id="extra_meal_charge"></span>
                                                            <span class="btn btn-sm btn-default" id="remove_extra_meal"><i class="fa fa-times" aria-hidden="true"></i></span>
                                                            </div>
                                                    </div>
                                                    <div class="baggagecharge-agent" id="extra_seat_charge_label" style="display:none">
                                                        <div class="col-xs-8 nopadding">Seat Charge</div>
                                                            <div class="col-xs-4 nopadding text-right">' . $currency_symbol . '
                                                            <span class="amnter arimobold" id="extra_seat_charge"></span>
                                                            <span class="btn btn-sm btn-default" id="remove_extra_seat"><i class="fa fa-times" aria-hidden="true"></i></span>
                                                            </div>
                                                    </div>
                                                    ';

    $extar_service_charge_details .= '</div>';

    $grand_total = '
                        <div class="clearfix"></div>
                        <div class="reptalltftr">
                            <div class="col-xs-8 nopadding">
                                <div class="farestybig">Grand Total</div>
                            </div>
                            <div class="col-xs-4 nopadding">
                            <span class="grand_total_amountorg" hidden>' . round($total_published_fare) . '</span>
                                <div class="amnterbig arimobold">' . $currency_symbol . ' <span class="grand_total_amount">' . round($total_published_fare) . '</span>  </div>
                            </div>
                        </div>';
    //Net Fare Details
    $hnf_details = '<div class="collapse" id="net_fare_details">
                                    <div class="kindrest">
                                        <div class="freshd">Fare Details</div>
                                        <div class="reptallt">
                                            <div class="col-xs-8 nopadding">
                                                <div class="faresty">Total Pub. Fare</div>
                                            </div>
                                            <div class="col-xs-4 nopadding">
                                                <div class="amnter arimobold">' . round($total_published_fare + $discount) . ' </div>
                                            </div>
                                        </div>
                                        <div class="reptallt">
                                            <div class="col-xs-8 nopadding">
                                                <div class="faresty">Markup</div>
                                            </div>
                                            <div class="col-xs-4 nopadding">
                                                <div class="amnter arimobold">+' . $FareDetails['_Markup'] . ' </div>
                                            </div>
                                        </div>
                                        <div class="reptallt">
                                            <div class="col-xs-8 nopadding">
                                                <div class="faresty">Discount</div>
                                            </div>
                                            <div class="col-xs-4 nopadding">
                                                <div class="amnter arimobold">-' . $discount . ' </div>
                                            </div>
                                        </div>
                                        <div class="reptallt">
                                            <div class="col-xs-8 nopadding">
                                                <div class="faresty">Comm. Earned</div>
                                            </div>
                                            <div class="col-xs-4 nopadding">
                                                <div class="amnter arimobold">+' . $FareDetails['_Commission'] . ' </div>
                                            </div>
                                        </div>
                                        <div class="reptallt">
                                        <div class="col-xs-8 nopadding">
                                            <div class="faresty">TdsOnCommission</div>
                                        </div>
                                        <div class="col-xs-4 nopadding">
                                            <div class="amnter arimobold">-' . $FareDetails['_tdsCommission'] . ' </div>
                                        </div>
                                        </div>' . $gst_data1 . '
                                        <div class="reptallt_commisn">
                                        <div class="col-xs-6 nopadding">
                                            <div class="farestybig">Total Payable</div>
                                        </div>
                                        <div class="col-xs-6 nopadding">
                                            <div class="amnterbig">' . $currency_symbol . ' <span id="agent_payable_amount">' . round($total_payable) . '</span> </div>
                                        </div>
                                        </div>
                                        <div class="reptallt_commisn">
                                        <div class="col-xs-8 nopadding">
                                            <div class="farestybig">Total Earned</div>
                                        </div>
                                        <div class="col-xs-4 nopadding">
                                            <div class="amnterbig ">' . $currency_symbol . ' ' . $FareDetails['_AgentEarning'] . ' </div>
                                        </div>
                                        </div>
                                    </div>
                                    </div>';
    $fare_summary .= $hide_show_fare_details;
    $fare_summary .= $hnf_details;
    $fare_summary .= '<div id="published_fare_details">';
    $fare_summary .= $pax_base_fare_details;
    $fare_summary .= $pax_tax_details;
    $fare_summary .= $extar_service_charge_details;
    $fare_summary .= $grand_total;
    $fare_summary .= '</div>';
    $fare_summary .= '</div>
                </div>';

    return $fare_summary;
}

function diaplay_phonecode($phone_code, $active_data, $user_country_code)
{


    $list = '';
    foreach ($phone_code as $code) {
        if (!empty($user_country_code)) {
            if ($user_country_code == $code['country_code']) {
                $selected = "selected";
            } else {
                $selected = "";
            }
        } else {

            // if ($active_data['api_country_list_fk'] == $code['origin']) {
            if ($user_country_code == $code['country_code']) {
                $selected = "selected";
            } else {
                $selected = "";
            }
        }
        $list .= "<option value=" . $code['name'] . " " . $code['country_code'] . "  " . $selected . " >" . $code['name'] . " " . $code['country_code'] . "</option>";
    }
    return $list;
}
?>
<script>
    $(document).ready(function(e) {
        $('.ticket_type_cls').click(function() {
            var ticket_type = $(this).val();
            $('#ticket_method').val(ticket_type);
        });
        $('#hide_show_net_fare').click(function() {
            if ($(this).hasClass('show_details') == true) {
                $(this).removeClass('show_details').addClass('hide_details');
                $(this).empty().html('-HNF');
                $('#published_fare_details').hide();
            } else if ($(this).hasClass('hide_details') == true) {
                $(this).removeClass('hide_details').addClass('show_details');
                $(this).empty().html('+SNF');
                $('#published_fare_details').show();
            }
        });
        $('#gst_number, #billing-email, #booking_user_name').on('keypress', function(e) {
            if (e.which == 32) {
                return false;
            }
        });
        $("#redeem_points").change(function() {

            var amount = $(".discount_total").text();
            //alert(amount);
            var rdv = $(".reduceamount").val();
            var grandtotal = $(".grand_total_amountorg").html();
            var disc = $(".discountorg").html();

            var div = $('#prompform');
            if ($(this).prop("checked") == true) {
                div.hide();
                $("#reward_tab").removeClass("hide");
                $(".redeem_points_post").val(1);
                $('#booking_amount').html('<?= $reward_usable ?>' + ' Points');
                // alert('true');
                $(".discount_total").text('<?php echo $this->currency->get_currency_symbol($pre_booking_params['default_currency']) . " " . round($price['api_total_display_fare_with_rewards'] + $convenience_fees, 2); ?>');
                var nedi = parseInt(disc) + parseInt(rdv);
                var nedi2 = parseInt(grandtotal) - parseInt(rdv);
                $(".discount").html(nedi);
                $("#total_booking_amount").html(nedi2);
                $(".grand_total_amount").html(nedi2);

                //$('.discount_total').animateNumber({ number: '<?php echo $total_price_with_rewards + $convenience_fees; ?>' });
                //alert('<?php echo $total_price_with_rewards + $convenience_fees; ?>');
                // $('#total_price_with_rewards').val('<?php echo $ret_data['grand_total_without_rewards']; ?>');
                // $("#prompform").hide();


            } else {

                div.show();
                //$reward_usable
                $("#reward_tab").addClass("hide");
                $('#booking_amount').html('0 Points');
                $(".redeem_points_post").val(0);
                var nedi = parseInt(disc);
                var nedi2 = parseInt(grandtotal);
                $(".discount").html(nedi);
                $("#total_booking_amount").html(nedi2);
                $(".grand_total_amount").html(nedi2);
                $(".discount_total").text('<?php echo $this->currency->get_currency_symbol($pre_booking_params['default_currency']) . " " . round($price['api_total_display_fare'] + $convenience_fees, 2); ?>');
                //$('.discount_total').animateNumber({ number: '<?php echo $ret_data['grand_total_without_rewards']; ?>' });
                // $('#total_price_with_rewards').val(0);
                // $("#prompform").show(); 

            }

        });

        $(document).ready(function() {
            $("#redeem_points").click();
            if ($("#redeem_points").prop("checked") == true) {
                $('#prompform').hide();
            }
        });
    });
</script>
<?php
//Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_session_expiry_script.js'), 'defer' => 'defer');
// changes start agent booking page and sidebar: commented this line
// Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('provablib.js'), 'defer' => 'defer');
// changes end agent booking page and sidebar: commented this line
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/booking_script.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_booking.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_extra_services.js'), 'defer' => 'defer');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('flight_extra_services.css'), 'media' => 'screen');
?>
<script type="text/javascript">
    /*
     session time out variables defined
     */
    var search_session_expiry = "<?php echo $GLOBALS['CI']->config->item('flight_search_session_expiry_period'); ?>";
    var search_session_alert_expiry = "<?php echo $GLOBALS['CI']->config->item('flight_search_session_expiry_alert_period'); ?>";
    var search_hash = "<?php echo $session_expiry_details['search_hash']; ?>";
    var start_time = "<?php echo $session_expiry_details['session_start_time']; ?>";
    var session_time_out_function_call = 1;
</script>
<!-- <script>
       //need these variables for global use
       var availablePerPassengerPlansList;
       var availableFamilyPlansList;
    function showModal() {
        let popupContainer = document.getElementById("modal-container");
        const couponContainer = document.querySelector(".insurance-modal");
        const closeBtn = document.querySelector(".insurance-modal .close-button");
        const loadingContainer = document.querySelector(".loading__insurance");
        const plansContainer = document.querySelector('.insurance__list');
        const unorderedList = document.createElement("ul");
        const buttonContainer = document.querySelector(".plans__button-container");
        const insuranceIdInput = document.getElementById("insurance_id");

        plansContainer.appendChild(unorderedList);
        popupContainer.style.display = "flex";
        couponContainer.classList.add("active");
        buttonContainer.style.display = "none";
     

        const baseUrl = "<?php echo base_url(); ?>";
        const fetchUrl = baseUrl + `index.php/flight/GetAvailablePlansOTAWithRiders/<?php echo $searchId ?>/<?php echo $segmentDetails ?>`;
        

        // Send request to fetch available insurance plans
        fetch(fetchUrl)
            .then(response => response.json())
            .then(plans => {
                // Hide loading animation
                loadingContainer.style.display = "none";
                buttonContainer.style.display ="flex";
                availablePerPassengerPlansList = plans.perPassengerPlans;
                availableFamilyPlansList = plans.familyPlans;
                insuranceIdInput.value = plans.id;

                showFamilyPlans();
            })
            .catch(error => {
                console.error("Error fetching available plans:", error);
                // Handle error k garney
            });

        closeBtn.addEventListener("click", () => {
            popupContainer.classList.remove("active");
            popupContainer.style.display = "none";
        });
        // Close the popup when clicking outside of it
        window.addEventListener("click", function(event) {
            if (event.target === popupContainer) {
                popupContainer.style.display = "none";
            }


        });
    }
function showFamilyPlans(){
const planContainer = document.querySelector(".insurance__list");
planContainer.innerHTML='';
const plans = availableFamilyPlansList;
for (const planKey in plans) {
  if (plans.hasOwnProperty(planKey)) {
    const plan = plans[planKey];
    const planDiv = document.createElement("div");
    planDiv.classList.add("plan");

    // Create HTML content for the plan
    const planHTML = `
    
      <h2>${plan.PlanTitle}</h2>
      <p><strong>Plan Code:</strong> ${plan.PlanCode}</p>
      <p><strong>Total Premium Amount:</strong> ${plan.CurrencyCode} ${plan.TotalPremiumAmount}</p>
    `;

    // Set the HTML content for the plan div
    planDiv.innerHTML = planHTML;

    // Append the plan div to the container
    planContainer.appendChild(planDiv);
  }
}

    }
    function showPerPassengerPlans(){
        console.log(availablePerPassengerPlansList);
        const planContainer = document.querySelector(".insurance__list");
        planContainer.innerHTML='';
        // const plans = availableFamilyPlansList;
         // Iterate through each plan
//   for (const planType in plans) {
//     if (plans.hasOwnProperty(planType)) {
//       const plan = plans[planType];
      
//       // Create HTML elements for plan details
//       const planDiv = document.createElement('div');
//       planDiv.classList.add('plan');
      
//       const planTitle = document.createElement('h3');
//       planTitle.textContent = plan.PlanTitle;
      
    //   const planButton = document.createElement('button');
    //   planButton.textContent = 'Select Plan';
    //   planButton.addEventListener('click', function() {
    //     // Append selected plan code to input value
    //     const selectedPlansInput = document.querySelector('#selected_plans');
    //     selectedPlansInput.value = plan.PlanCode;
    //     console.log(plan);
    //   });
      
//       // Append elements to container
//       planDiv.appendChild(planTitle);
//       planDiv.appendChild(planButton);
//       planContainer.appendChild(planDiv);
//     }
//   }
        const plans = availablePerPassengerPlansList;
for (const planKey in plans) {
  if (plans.hasOwnProperty(planKey)) {
    const plan = plans[planKey];
    console.log(plan);
    const planDiv = document.createElement("div");
    planDiv.classList.add("plan");

    // Create HTML content for the plan
    const planHTML = `
      <p>${plan.PlanTitle}</p>
      <p><strong>Total Premium Amount:</strong> ${plan.CurrencyCode} ${plan.TotalPremiumAmount}</p>
      ${plan.PlanContent}
    `;
    const planButton = document.createElement('button');
      planButton.textContent = 'Select Plan';
      planButton.addEventListener('click', function() {
        // Append selected plan code to input value
        const selectedPlansInput = document.querySelector('#selected_plans');
        selectedPlansInput.value = plan.PlanCode;
        console.log(plan);
      });
    // Set the HTML content for the plan div
    planDiv.innerHTML = planHTML;
    planDiv.appendChild(planButton);

    // Append the plan div to the container
    planContainer.appendChild(planDiv);
  }
}
    }
</script> -->
<!-- const baseUrl = "<?php echo base_url(); ?>";
        const fetchUrl = baseUrl + `index.php/flight/GetAvailablePlansOTAWithRiders/<?php echo $searchId ?>/<?php echo $segmentDetails ?>`;
        modalBody.innerHTML = ' <img id ="loading" src="<?php echo SYSTEM_IMAGE_DIR . "loading.gif" ?>" alt="Loading..." />'; -->
        <script>
document.getElementById("yesBtn").addEventListener("click", openModal);

const modal = document.getElementById("modal");
const modalBody = document.getElementById("modal-body");
const closeBtn = document.getElementsByClassName("close")[0];

let selectedPlans = [];
let currentPassenger = 1;
const totalPassengers = <?php echo $total_pax_count; ?>; // Get the total passenger count from PHP
let familyPlanSelected = false; // Flag to track if a family plan is already selected

const baseUrl = "<?php echo base_url(); ?>";
const fetchUrl = `${baseUrl}index.php/flight/GetAvailablePlansOTAWithRiders/<?php echo $searchId ?>/<?php echo $segmentDetails ?>`;

closeBtn.onclick = function() {
    resetModal();
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target === modal) {
        resetModal();
        modal.style.display = "none";
    }
}

function openModal() {
    if (!arePassengerNamesEntered()) {
        displayErrorMessage("Please enter the names first.");
        return;
    }

    modal.style.display = "block";
    showLoading();

    fetch(fetchUrl)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            showPlanOptions(data);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            hideLoading();
            modalBody.innerHTML = '<p>Error loading data. Please try again later.</p>';
        });
}

function arePassengerNamesEntered() {
    for (let i = 1; i <= totalPassengers; i++) {
        const firstName = document.getElementById(`passenger-first-name-${i}`).value.trim();
        const lastName = document.getElementById(`passenger-last-name-${i}`).value.trim();
        if (!firstName || !lastName) {
            return false;
        }
    }
    return true;
}

function displayErrorMessage(message) {
    const errorDiv = document.getElementById("errorDiv");
    if (errorDiv) {
        errorDiv.innerText = message;
    } else {
        const errorMessage = document.createElement("div");
        errorMessage.id = "errorDiv";
        errorMessage.style.color = "red";
        errorMessage.innerText = message;
        document.getElementById("yesNoBtnDiv").appendChild(errorMessage);
    }
}

function showLoading() {
    modalBody.innerHTML = '<img id="loading" src="<?php echo SYSTEM_IMAGE_DIR . "loading.gif"; ?>" alt="Loading...">';
}

function hideLoading() {
    const loadingImg = document.getElementById("loading");
    if (loadingImg) {
        loadingImg.style.display = "none";
    }
}

function showPlanOptions(data) {
    modalBody.innerHTML = `
        <p>Which plan would you like to purchase?</p>
        <button id="familyPlanBtn">Family Plan</button>
        <button id="individualPlanBtn">Individual Plan</button>
    `;

    document.getElementById("familyPlanBtn").addEventListener("click", () => showFamilyPlans(data.familyPlans));
    document.getElementById("individualPlanBtn").addEventListener("click", () => showIndividualPlans(currentPassenger, data.perPassengerPlans));
}

function showFamilyPlans(familyPlans) {
    modalBody.innerHTML = `
        <button id="goBackBtn">Go Back</button>
        <div id="familyPlans">
            <!-- Family plans will be loaded here -->
        </div>
    `;

    document.getElementById("goBackBtn").addEventListener("click", openModal);

    const familyPlansContainer = document.getElementById("familyPlans");

    let planExists = false;
    Object.values(familyPlans).forEach(plansArray => {
        if (plansArray.length > 0) {
            planExists = true;
            plansArray.forEach((plan, index) => {
                const planElement = document.createElement('div');
                planElement.innerHTML = `
                    <h3>${plan.PlanTitle}</h3>
                    <p>${plan.CurrencyCode} ${plan.TotalPremiumAmount}</p>
                    <button id="selectFamilyPlan${index}" class="family-plan-select">Select</button>
                    <button id="viewDetailsFamilyPlan${index}" class="view-details">View Details</button>
                    <div id="detailsFamilyPlan${index}" style="display:none;">${plan.PlanContent}</div>
                `;
                familyPlansContainer.appendChild(planElement);

                document.getElementById(`viewDetailsFamilyPlan${index}`).addEventListener("click", () => {
                    const details = document.getElementById(`detailsFamilyPlan${index}`);
                    details.style.display = details.style.display === "none" ? "block" : "none";
                });

                document.getElementById(`selectFamilyPlan${index}`).addEventListener("click", () => {
                    if (!familyPlanSelected) {
                        selectedPlans = selectedPlans.filter(plan => plan.type !== 'Family'); // Remove any previously selected family plan
                        selectedPlans.push({
                            type: 'Family',
                            plan: plan.PlanTitle,
                            planId: plan.PlanCode
                        });
                        familyPlanSelected = true;
                        console.log(`Selected ${plan.PlanTitle}`);
                        appendSelectedPlan(plan.PlanTitle, plan.PlanCode, 'Family');
                        finishSelection(); // Directly finish selection
                    }
                });
            });
        }
    });

    if (!planExists) {
        familyPlansContainer.innerHTML = '<p>No family plans available.</p>';
    }
}

function showIndividualPlans(passenger, individualPlans) {
    const passengerName = getPassengerName(passenger);
    modalBody.innerHTML = `
        <button id="goBackBtn">Go Back</button>
        <div id="individualPlans">
            <h2>Select a plan for ${passengerName}</h2>
            <!-- Individual plans will be loaded here -->
        </div>
    `;

    document.getElementById("goBackBtn").addEventListener("click", () => {
        if (passenger === 1) {
            openModal();
        } else {
            showIndividualPlans(passenger - 1, individualPlans);
        }
    });

    const individualPlansContainer = document.getElementById("individualPlans");

    let planExists = false;
    Object.values(individualPlans).forEach(plan => {
        if (plan) {
            planExists = true;
            const planElement = document.createElement('div');
            planElement.innerHTML = `
                <h3>${plan.PlanTitle}</h3>
                <p>${plan.CurrencyCode} ${plan.TotalPremiumAmount}</p>
                <button id="selectIndividualPlan${plan.PlanCode}" class="individual-plan-select">Select</button>
                <button id="viewDetailsIndividualPlan${plan.PlanCode}" class="view-details">View Details</button>
                <div id="detailsIndividualPlan${plan.PlanCode}" style="display:none;">${plan.PlanContent}</div>
            `;
            individualPlansContainer.appendChild(planElement);

            document.getElementById(`viewDetailsIndividualPlan${plan.PlanCode}`).addEventListener("click", () => {
                const details = document.getElementById(`detailsIndividualPlan${plan.PlanCode}`);
                details.style.display = details.style.display === "none" ? "block" : "none";
            });

            document.getElementById(`selectIndividualPlan${plan.PlanCode}`).addEventListener("click", () => {
                selectedPlans = selectedPlans.filter(p => !(p.type === 'Individual' && p.passenger === passengerName)); // Remove any previously selected plan for this passenger
                selectedPlans.push({
                    type: 'Individual',
                    passenger: passengerName,
                    plan: plan.PlanTitle,
                    planId: plan.PlanCode
                });
                console.log(`Selected ${plan.PlanTitle} for ${passengerName}`);
                appendSelectedPlan(plan.PlanTitle, plan.PlanCode, passengerName);
                if (passenger < totalPassengers) {
                    showIndividualPlans(passenger + 1, individualPlans);
                } else {
                    finishSelection();
                }
            });
        }
    });

    if (!planExists) {
        individualPlansContainer.innerHTML = '<p>No individual plans available.</p>';
    }

    const skipButton = document.createElement('button');
    skipButton.innerText = 'Skip';
    skipButton.addEventListener('click', () => {
        selectedPlans = selectedPlans.filter(p => !(p.type === 'Individual' && p.passenger === passengerName)); // Ensure no previous selection
        selectedPlans.push({
            type: 'Individual',
            passenger: passengerName,
            plan: 'None'
        });
        if (passenger < totalPassengers) {
            showIndividualPlans(passenger + 1, individualPlans);
        } else {
            finishSelection();
        }
    });
    individualPlansContainer.appendChild(skipButton);
}

function getPassengerName(index) {
    const firstName = document.getElementById(`passenger-first-name-${index}`).value;
    const lastName = document.getElementById(`passenger-last-name-${index}`).value;
    return `${firstName} ${lastName}`;
}

function appendSelectedPlan(planTitle, planId, passenger) {
    const selectedPlanInput = document.getElementById("selectedPlanInput");
    const selectedPlanIdInput = document.getElementById("selectedPlanIdInput");

    selectedPlanInput.value += `${passenger}: ${planTitle}; `;
    selectedPlanIdInput.value += `${passenger}: ${planId}; `;
}

function finishSelection() {
    const selectedPlansInput = document.getElementById("selectedPlansJson");
    selectedPlansInput.value = JSON.stringify(selectedPlans);

    document.getElementById("insuranceSection").style.display = "none";
    document.getElementById("insurancePlans").style.display = "none";
    
    const selectedPlansDiv = document.createElement('div');
    selectedPlansDiv.innerHTML = `
        <h2>Selected Plans</h2>
        <ul>
            ${selectedPlans.map(plan => `<li>${plan.type === 'Individual' ? plan.passenger : 'Family'}: ${plan.plan}</li>`).join('')}
        </ul>
        <button id="cancelBtn">Cancel</button>
    `;
    document.getElementById("insuranceSection").parentNode.appendChild(selectedPlansDiv);

    document.getElementById("cancelBtn").addEventListener("click", () => {
        selectedPlansDiv.remove();
        selectedPlans = [];
        const selectedPlansInput = document.getElementById("selectedPlansJson");
    selectedPlansInput.value = '';

        document.getElementById("insuranceSection").style.display = "block";
        resetModal();
    });

    // Close the modal
    resetModal();
    modal.style.display = "none";
}


function resetModal() {
    modalBody.innerHTML = "";
    selectedPlans = [];
    currentPassenger = 1;
    familyPlanSelected = false;
    const selectedPlanInput = document.getElementById("selectedPlanInput");
    const selectedPlanIdInput = document.getElementById("selectedPlanIdInput");
    selectedPlanInput.value = "";
    selectedPlanIdInput.value = "";
}

</script>