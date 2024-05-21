<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= $___favicon_ico ?>" type="image/x-icon" />
    <title>Make Payment </title>

    <!-- added google tag manager -->
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-K4PP7X5J');
    </script>
    <!-- End Google Tag Manager -->
    <!--change 1 for session expiry added following view-->
<!-- <echo $GLOBALS['CI']->template->isolated_view('share/flight_session_expiry_popup'); ?> -->
<style>
    /* Apply a reset to remove default margin and padding */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;


    }

    .container-payment-details {

        max-width: 1200px;
        margin: 0 auto;
        font-family: monospace;
        /* Center the container horizontally */
        margin-top: 0;
        margin-bottom: 0;
        padding: 20px;
        font-size: 16px;
        box-sizing: border-box;
        /* Include padding in the container's width */
        justify-content: center;
    }

    .card-container {
        display: flex;
        justify-content: center;
        gap: 0px;
    }

    .reverse-on-mobile {
        display: flex;
    }

    .card {
        width: 80%;
        background-color: #fff;
        box-shadow: 5px 10px 15px purple;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;

    }

    .card-header {
        font-size: 20px;
        margin-bottom: 20px;
        font-family: monospace;
        font: bold;
        color: white;
    }

    .card-footer {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .card.left-card.card-body {
        display: block;
    }

    .card.left-card {
        background: linear-gradient(120deg, #8F53A1, purple);
        color: #fff;
        max-width: 500px;
        padding: 20px;
        border-top-right-radius: 0px;
        border-bottom-right-radius: 0px;
    }

    .card.right-card {
        background-color: white;
        max-width: 200px;
        padding: 20px;
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
        display: flex;
        align-items: center;
    }

    .selected-gateway-image {
        display: grid;
        place-content: center;
        padding: 10px;
        width: 100%;
    }

    .header {
        font-size: 48px;
        font-weight: bold;
        margin-bottom: 20px;
        color: white;
    }

    table {
        width: 100%;
    }

    .button {
        background-image: linear-gradient(to right, #15779e, #0BA0DC);
        color: #fff;
        padding: 10px 20px;
        border: solid;
        border-color: #fff;
        border-width: 1px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        font-size: large;
        margin-top: 20px;
    }

    .button-proceed-to-payment:hover {
        background-color: #fff;
        color: #000;
    }

    .button-proceed-style:hover {
        background-image: linear-gradient(to right, #0BA0DC, #8F53A1);
    }

    /* Image buttons */
    .image-buttons {
        display: flex;
        justify-content: space-evenly;
        width: 700px;
        align-items: center;
        gap: 10px;
    }

    /* Individual image button */
    .image-button {
        flex: 1;
        padding: 10px;
        height: 80px;
        display: grid;
        place-content: center;
        box-shadow: 0 2px 4px rgba(0, 0.2, 0, 0.2);
    }

    @media screen and (max-width: 768px) {
        .card {
            width: 95%;
        }

        .image-buttons {
            width: 95%;
        }
    }

    @media screen and (max-width: 637px) {
        .container-payment-details {
            width: 80%;
        }

        .card-container {
            flex-wrap: wrap;
            width: 100%;
        }

        .card.left-card {
            padding: 5px;
        }

        .reverse-on-mobile {
            display: flex;
            flex-direction: column-reverse;
        }

        .card {
            width: 100%;
        }

        .card.left-card {
            max-width: 100%;
            margin-top: 0;
            border-top-left-radius: 0px;
            border-bottom-right-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .card.right-card {
            max-width: 100%;
            margin-bottom: 0;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 0px;
        }

        .image-buttons {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .title-text {
            font-size: 30px;
        }

        .selected-gateway-image {
            padding: 0 110px;
        }
    }

    @media screen and (max-width: 545px) {
        .container-payment-details {
            width: 100%;
        }

        .button {
            padding: 5px 10px;
            font-size: large;
        }

        .selected-gateway-image {
            padding: 0 100px;
        }
    }

    @media screen and (max-width: 480px) {
        .card-container {
            flex-wrap: wrap;
            width: 100%;
        }

        .reverse-on-mobile {
            display: flex;
            flex-direction: column-reverse;
        }

        .card.left-card {
            margin-top: 0;
            border-top-left-radius: 0px;
            border-bottom-right-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .card.right-card {
            max-width: 100%;
            margin-bottom: 0;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 0px;
        }

        .image-buttons {
            width: 100%;
            flex-wrap: wrap;
        }
    }

    @media screen and (max-width: 419px) {
        .button {
            font-size: 14px;
        }

        .selected-gateway-image {
            padding: 0 50px;
        }
    }

    @media screen and (max-width: 376px) {
        .button {
            font-size: 12px;
        }

        .title-text {
            font-size: 28px;
        }
    }

    @media screen and (max-width: 337px) {
        .container-payment-details {
            font-size: medium;
        }

        .button {
            padding: 3px 5px;
            font-size: 10px;
        }

        .title-text {
            font-size: 24px;
        }
    }

    @media screen and (max-width: 291px) {
        .button {
            padding: 3px 5px;
            font-size: 10px;
        }

        .title-text {
            font-size: 22px;
        }
    }
</style>
</head>
<!--changes start session: added this line-->
<!-- < $GLOBALS['CI']->template->isolated_view('share/flight_session_expiry_popup'); ?> -->
<!--changes end session: added this line-->
<!--changes added following php code  section for convenience fees-->
<?php

$activeGateways = $this->config->config['active_payment_gateway'];
$convDetails = array();
foreach ($activeGateways as $k => $v) {
    $status = $this->custom_db->single_table_records('convenience_fees', '*', array('module' => $activeGateways[$k]));
    if ($status['status'] != 0) {
        array_push($convDetails, $status);
    }
}
// debug($convDetails);die;
$convDetails = json_encode($convDetails);
?>

<body style="background: #fff;">
       <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4PP7X5J"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
     <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11441192494"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'AW-11441192494');
</script>
    <div class="container-payment-details">
        <!--changes added following input tag for convenience fees-->
        <input id="conv" type="text" value="<?php echo htmlspecialchars($convDetails); ?>" readonly hidden>
        <?php $jsonArray_post_params = json_encode($post_params); ?>
        <input id="post-params" type="text" value="<?php echo htmlspecialchars($jsonArray_post_params); ?>" readonly hidden>
        <input id="pay-method-pay-now" type="text" value="<?php echo PAY_NOW; ?>" readonly hidden>
        <input id="pay-method-pay-at-bank" type="text" value="<?php echo PAY_AT_BANK; ?>" readonly hidden>
        <input id="pay-method-pay-with-esewa" type="text" value="<?php echo PAY_WITH_ESEWA; ?>" readonly hidden>
        <input id="pay-method-pay-with-khalti" type="text" value="<?php echo PAY_WITH_KHALTI; ?>" readonly hidden>
        <!-- changes added new input field for nica -->
        <input id="pay-method-pay-with-nica" type="text" value="<?php echo PAY_WITH_NICA; ?>" readonly hidden>
        <h2 class="text-center title-text" style="color:purple; margin-bottom:20px; font-weight:800; font-size:30px;">SELECT PAYMENT MODE</h3>
            <div class="card-container reverse-on-mobile">
                <div class="card left-card">
                    <div class="card-header">Payment Details</div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td>Total Fare</td>
                                <td class="text-right" id="total-fare"></td>
                            </tr>
                            <tr>
                                <td>Convenience Fee</td>
                                <td class="text-right" id="convenience-fee"></td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td class="text-right" id="discount"></td>
                            </tr>
                        </table>
                        <hr>
                        <table>
                            <tr>
                                <td>Total Payable Amount</td>
                                <td class="text-right" id="total-payable-amount"></td>
                            </tr>
                        </table>
                        <hr>
                        <table>
                            <tr>
                                <td>Cashback</td>
                                <td class="text-right" id="cashback"></td>
                            </tr>
                        </table>
                        <br>
                    </div>
                    <div class="card-footer">
                        <input id="proceed-bool" type="text" value="0" hidden readonly>
                        <a href="#" class="button button-proceed-to-payment">Proceed to Payment <i class="fas fa-arrow-right"></i></a>
                        <div class="proceed-error d-none" style="background-color:red; margin-top:20px"></div>
                    </div>
                </div>
                <div class="card right-card">
                    <div class="selected-gateway-image"></div>
                </div>
            </div>
            <div style="display: flex; justify-content: center;">
                <div class="image-buttons"></div>
            </div>
                   <form action="<?=base_url() . 'index.php/flight/pre_booking/' . $post_params['search_id'] ?>" method="POST" autocomplete="off" id="pre-booking-form-1" hidden>
                <input id="proceed-form-data" name="proceed-form-data" type="text" value="">
            </form>
   
    </div>
</body>
    <!--changes start session: = change 2-->
<!--change 2 for session expiry added this js file-->
<!-- < Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/flight_session_expiry_script.js'), 'defer' => 'defer'); ?> -->
<script type="text/javascript">
// changes start session: = change 3
// change 3 for session expiry added the following
    // let jsonArrayString_post_params_1 = $('#post-params').val();
    // let post_params_1 = JSON.parse(jsonArrayString_post_params_1);
    // let session_time_out_function_call = 0;
//     if(post_params_1.enable_session_expiry == true) {
//         //  session time out variables defined
//         // if(post_params_1.session_module == 'flight') {
//         //     var search_id = post_params_1.search_id;
//         //     var redirect_url_after_session = app_base_url + "index.php/flight/search/" + search_id;
//         //     var search_session_expiry = "<?php echo $GLOBALS['CI']->config->item('flight_search_session_expiry_period'); ?>";
//         //     var search_session_alert_expiry = "<?php echo $GLOBALS['CI']->config->item('flight_search_session_expiry_alert_period'); ?>";
//         //     var add_sess_time = <?php echo $GLOBALS['CI']->config->item('payent_gateway_selection_page_add_sess_time'); ?>;
//         // }
//         var search_hash = post_params_1.search_hash;
//         var start_time = post_params_1.session_start_time;
//         start_time = parseInt(start_time) + parseInt(add_sess_time);
//         // changes start session: added code block
// var sub_start_time = "<?php echo $GLOBALS['CI']->config->item('flight_search_session_sub_start_time'); ?>";
//         session_time_out_function_call = 1;
//     }
    
// document.cookie = "myJavascriptVarSearchHashLast = " + search_hash;
//             <?php
//                 $last_search_hash = $_COOKIE['myJavascriptVarSearchHashLast'];
//                 $is_session_expired = $GLOBALS['CI']->session->userdata($last_search_hash."_isExpired");
//             ?>
//             let isSessionExpired = <?php echo json_encode($is_session_expired); ?>;
//             if (isSessionExpired) {
//                 start_time = 0;
//                 sub_start_time = 0;
//             }
//upto here
    $(document).ready(function() {
        let jsonArrayString_post_params = $('#post-params').val();
        let post_params = JSON.parse(jsonArrayString_post_params);

        //changes start of addition of convenience fees 1
        let jsonConvDetails = $('#conv').val();

        let convDetails = JSON.parse(jsonConvDetails);

        let newArray = [];
        let esewaConvinienceFee = 0;
        let cipsConvinienceFee = 0;
        let khaltiConvinienceFee = 0;
        let fonepayConvinienceFee = 0;
        // changes new variable for nica convenience_fee
        let nicaConvinienceFee = 0;
        let pgConvTotalAmount = 0;        //changes end of addition of convenience fees 1 

        //changes Start of modification of code for consideration of reward points
        let total_amount_val;
        let discount;
        let isRewardPoints = ((post_params['reward_point'] > 0) && (post_params['reward_amount'] > 0)) ? true : false;

        // console.log(isRewardPoints);
        if (isRewardPoints == true) {
            total_amount_val = parseFloat(post_params['total_amount_val']) + parseFloat(post_params['reward_amount']) + parseFloat(post_params['promocode_discount']);
            discount = parseFloat(post_params['promocode_discount']) + parseFloat(post_params['reward_amount']);

        } else {
            total_amount_val = parseFloat(post_params['total_amount_val']);
            discount = parseFloat(post_params['promocode_discount']);
        }
        //changes  End of modification of code for consideration of reward points
        // changes removed for consideration of reward points
        // let total_amount_val = parseFloat(post_params['total_amount_val']);
        let convenience_fee = parseFloat(post_params['convenience_fees']);
        // changes removed for consideration of reward points
        // let discount = parseFloat(post_params['promocode_discount']);
        let total_payable_amount = total_amount_val + convenience_fee - discount;
        //changes start for convenience fee 2
        if (convDetails.length > 0 && convDetails != undefined) {
            convDetails.forEach(convDetail => {

                newArray[`${convDetail['data'][0]['module']}`] = convDetail['data'][0];
            });
            for (let k in newArray) {
                if (newArray[k]['value_type'] == 'percentage') {
                    newArray[k]['value'] = (newArray[k]['value'] / 100) * total_amount_val;

                }


                if (k.toLocaleLowerCase() == 'fonepay') {
                    fonepayConvinienceFee = parseFloat(newArray[k]['value']) ?? 0;
                } else if (k.toLocaleLowerCase() == 'esewa') {
                    esewaConvinienceFee = parseFloat(newArray[k]['value']) ?? 0;
                } else if (k.toLocaleLowerCase() == 'connect') {
                    cipsConvinienceFee = parseFloat(newArray[k]['value']) ?? 0;
                } else if (k.toLocaleLowerCase() == 'khalti') {
                    khaltiConvinienceFee = parseFloat(newArray[k]['value']) ?? 0;
                    //changes added new elseif for nica
                } else if (k.toLocaleLowerCase() == 'nica') {
                    nicaConvinienceFee = parseFloat(newArray[k]['value']) ?? 0;
                }

            }
        }
        //changes end for convenience fee 2
        let cashback = parseFloat(0);
        $('#total-fare').text(post_params['currency'] + ' ' + total_amount_val);
        $('#convenience-fee').text(post_params['currency'] + ' ' + convenience_fee);
        $('#discount').text(post_params['currency'] + ' ' + discount);
        $('#total-payable-amount').text(post_params['currency'] + ' ' + total_payable_amount);
        $('#cashback').text(post_params['currency'] + ' ' + cashback);
        //changes added new nica field add nica here once redirection url is changed
        let gateways = ['cips', 'fonepay', 'esewa', 'khalti', 'nica'];
        $.each(gateways, function(index, gateway) {
            $('.image-buttons').append(`<div class="image-button button-${gateway}"><a href="#"><img src="<?php echo base_url() ?>/extras/system/template_list/template_v3/images/logos-payments/${gateway}.png" alt="${gateway}" style="width: 100px;"></a></div>`);

            $(document).on('click', '.button-' + gateway, function(e) {
                e.preventDefault();
                $('.proceed-error').hide();
                $('#proceed-bool').val(1);
                //changes added first two lines in each of the following cases
                switch (gateway) {
                    case 'esewa':

                        $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + esewaConvinienceFee));
                        $('#total-payable-amount').text(post_params['currency'] + ' ' + (total_payable_amount + esewaConvinienceFee));
                        gatewayRenamed = 'eSewa';
                        post_params['payment_method'] = $('#pay-method-pay-with-esewa').val();
                        break;
                    case 'khalti':
                        $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + khaltiConvinienceFee));
                        $('#total-payable-amount').text(post_params['currency'] + ' ' + (khaltiConvinienceFee + total_payable_amount));
                        gatewayRenamed = 'Khalti';
                        post_params['payment_method'] = $('#pay-method-pay-with-khalti').val();
                        break;
                    case 'fonepay':
                        $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + fonepayConvinienceFee));
                        $('#total-payable-amount').text(post_params['currency'] + ' ' + (total_payable_amount + fonepayConvinienceFee));
                        gatewayRenamed = 'FonePay';
                        post_params['payment_method'] = $('#pay-method-pay-now').val();
                        break;
                    case 'cips':
                        $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + cipsConvinienceFee));
                        $('#total-payable-amount').text(post_params['currency'] + ' ' + (total_payable_amount + cipsConvinienceFee));
                        gatewayRenamed = 'ConnectIPS';
                        post_params['payment_method'] = $('#pay-method-pay-at-bank').val();
                        break;
                        //changes added new case for nica
                    case 'nica':
                        $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + nicaConvinienceFee));
                        $('#total-payable-amount').text(post_params['currency'] + ' ' + (total_payable_amount + nicaConvinienceFee));
                        gatewayRenamed = 'NIC Asia';
                        post_params['payment_method'] = $('#pay-method-pay-with-nica').val();
                        break;
                    default:
                        break;
                }
                $('.selected-gateway-image').html("");
                $('.selected-gateway-image').html(`<img src='<?php echo base_url() ?>/extras/system/template_list/template_v3/images/logos-payments/${gateway}.png' alt=${gatewayRenamed} style="width: 100%;">`);
                $('.button-proceed-to-payment').html("");
                $('.button-proceed-to-payment').addClass('button-proceed-style');
                if (gateway != 'nica') {
                    $('.button-proceed-to-payment').html(`Proceed to ${gatewayRenamed} for payment <i class="fas fa-arrow-right"></i>`);
                } else {
                    $('.button-proceed-to-payment').html(`Proceed to card payment <i class="fas fa-arrow-right"></i>`);
                }
            });
        });
        $(document).on('click', '.button-proceed-to-payment', function(e) {
            e.preventDefault();
            if ($('#proceed-bool').val() == 0) {
                $('.proceed-error').show();
                $('.proceed-error').html('Please Select a Payment Method.');
            }
            if ($('#proceed-bool').val() == 1) {
//                              // changes start session: added code block
//                              if(post_params_1.session_module == 'flight') {
// document.cookie = "myJavascriptVarSearchHashLast = " + search_hash;
//                             <?php
//                                 $last_search_hash = $_COOKIE['myJavascriptVarSearchHashLast'];
//                                 $GLOBALS['CI']->session->set_userdata($last_search_hash."_isExpired", 1);
//                             ?>
//                 }
//                             // upto here
                //changes start for convenience fees 3
                let convFeeText = $('#convenience-fee').text();
                let convFeeArray = convFeeText.split(" ");
                let convFee = Number(convFeeArray[1]);
                post_params['pg_convenience'] = convFee;
                post_params['total_amount_val'] = total_payable_amount + convFee+ discount;
                //changes end for convenience fees 3  
                let jsonArrayString_post_params_1 = JSON.stringify(post_params);
                $('#proceed-form-data').val(jsonArrayString_post_params_1);
                // $('#pre-booking-form-1').attr('target', '_blank').submit();
                $('#pre-booking-form-1').submit();
            }
        });
    });
</script>
</html>