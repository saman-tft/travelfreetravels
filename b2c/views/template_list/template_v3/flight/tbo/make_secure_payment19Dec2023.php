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
        font-size:16px;
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
        background: linear-gradient(120deg, #8F53A1, purple );
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
        color:white;
    }

    table {
        width: 100%;
    }

    .button {
        background : #009edb;
        color: #fff;
        padding: 5px 20px;
        border: solid;
        border-color: #fff;
        border-width: 1px;
        border-radius: 40px;
        cursor: pointer;
        margin-top: 20px;
        font-size:12px;
    }

    .button-proceed-to-payment:hover {
        background-color: #fff;
        color: #000;
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

    @media screen and (max-width: 480px) {
        .card-container {
            flex-wrap: wrap;
            width: 100%;
        }

        .reverse-on-mobile {
            display: flex;
            flex-direction: column-reverse;
        }

        .card {
            width: 100%;
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
</style>

<body style="background: #fff;">
    <div class="container-payment-details">
        <?php $jsonArray_post_params = json_encode($post_params); ?>
        <input id="post-params" type="text" value="<?php echo htmlspecialchars($jsonArray_post_params); ?>" readonly hidden>
        <input id="pay-method-pay-now" type="text" value="<?php echo PAY_NOW; ?>" readonly hidden>
        <input id="pay-method-pay-at-bank" type="text" value="<?php echo PAY_AT_BANK; ?>" readonly hidden>
        <input id="pay-method-pay-with-esewa" type="text" value="<?php echo PAY_WITH_ESEWA; ?>" readonly hidden>
        <input id="pay-method-pay-with-khalti" type="text" value="<?php echo PAY_WITH_KHALTI; ?>" readonly hidden>
        <h2 class="text-center" style="color:purple; margin-bottom:20px; font-weight:800; font-size:30px;">SELECT PAYMENT MODE</h3>
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
                    <a href="#" class="button button-proceed-to-payment">Proceed to Pay <i class="fas fa-arrow-right"></i></a>
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
        <form action="<?= base_url() . 'index.php/flight/pre_booking/' . $post_params['search_id'] ?>" method="POST" autocomplete="off" id="pre-booking-form-1" hidden>
            <input id="proceed-form-data" name="proceed-form-data" type="text" value="">
        </form>
    </div>
</body>
<script type="text/javascript">
    $(document).ready(function() {
        let jsonArrayString_post_params = $('#post-params').val();
        let post_params = JSON.parse(jsonArrayString_post_params);
        let total_amount_val = parseFloat(post_params['total_amount_val']);
        let convenience_fee = parseFloat(post_params['convenience_fees']);
        let discount = parseFloat(post_params['promocode_discount']);
        let total_payable_amount = total_amount_val + convenience_fee-discount;
        let cashback = parseFloat(0);
        $('#total-fare').text(post_params['currency'] + ' ' + total_amount_val);
        $('#convenience-fee').text(post_params['currency'] + ' ' + convenience_fee);
        $('#discount').text(post_params['currency'] + ' ' + discount);
        $('#total-payable-amount').text(post_params['currency'] + ' ' + total_payable_amount);
        $('#cashback').text(post_params['currency'] + ' ' + cashback);

        let gateways = [ 'fonepay', 'cips','esewa', 'khalti'];
        $.each(gateways, function(index, gateway) {
            $('.image-buttons').append(`<div class="image-button button-${gateway}"><a href="#"><img src="<?php echo base_url() ?>/extras/system/template_list/template_v3/images/logos-payments/${gateway}.png" alt="${gateway}" style="width: 100px;"></a></div>`);
            $(document).on('click', '.button-' + gateway, function(e) {
                e.preventDefault();
                $('.proceed-error').hide();
                $('#proceed-bool').val(1);
                switch (gateway) {
                    case 'esewa':
                        gatewayRenamed = 'eSewa';
                        post_params['payment_method'] = $('#pay-method-pay-with-esewa').val();
                        break;
                        
                    case 'khalti':
                        gatewayRenamed = 'Khalti';
                        post_params['payment_method'] = $('#pay-method-pay-with-khalti').val();
                        break;
                        
                    case 'fonepay':
                        gatewayRenamed = 'FonePay';
                        post_params['payment_method'] = $('#pay-method-pay-now').val();
                        break;
                        
                    case 'cips':
                        gatewayRenamed = 'ConnectIPS';
                        post_params['payment_method'] = $('#pay-method-pay-at-bank').val();
                        break;
                        
                    default:
                        break;
                }
                $('.selected-gateway-image').html("");
                $('.selected-gateway-image').html(`<img src='<?php echo base_url() ?>/extras/system/template_list/template_v3/images/logos-payments/${gateway}.png' alt=${gatewayRenamed} style="width: 100%;">`);
                $('.button-proceed-to-payment').html("");
                $('.button-proceed-to-payment').html(`Proceed to ${gatewayRenamed} for payment <i class="fas fa-arrow-right"></i>`);
            });
        });
        $(document).on('click', '.button-proceed-to-payment', function(e) {
            e.preventDefault();
            if ($('#proceed-bool').val() == 0) {
                $('.proceed-error').show();
                $('.proceed-error').html('Please Select a Payment Method.');
            }
            if ($('#proceed-bool').val() == 1) {
                // console.log(post_params);
                let jsonArrayString_post_params_1 = JSON.stringify(post_params);
                // console.log(jsonArrayString_post_params_1);
                $('#proceed-form-data').val(jsonArrayString_post_params_1);
                // $('#pre-booking-form-1').attr('target', '_blank').submit();
                $('#pre-booking-form-1').submit();
            }
        });
    });
</script>