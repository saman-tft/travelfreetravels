<div class="container" style="display:flex;justify-content:center;align-items:center;">
    <div class="form-group">
      <label for="amount">Enter the amount</label>
      <input name="amount" type="number" class="form-control" id="amount">
      <div class="error-message" style="color: red; display: none;"></div>
    </div>
</div>
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
<!--changes added following php code  section for convenience fees-->
<?php

// $activeGateways = $this->config->config['active_payment_gateway'];
$activeGateways = $this->config->item('active_payment_gateway');
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
  <div class="container-payment-details">
    <!--changes added following input tag for convenience fees-->
    <input id="conv" type="text" value="<?php echo htmlspecialchars($convDetails); ?>" readonly hidden>
    <?php $jsonArray_post_params = json_encode($data); ?>
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
                <td>Total Amount</td>
                <td class="text-right" id="total-fare"></td>
              </tr>
              <tr>
                <td>Convenience Fee</td>
                <td class="text-right" id="convenience-fee"></td>
              </tr>
            </table>
            <hr>
            <table>
              <tr>
                <td>Total Payable Amount</td>
                <td class="text-right" id="total-payable-amount"></td>
              </tr>
            </table>
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

      <form action="<?= base_url() . 'index.php/payment_gateway/processTopup' ?>" method="POST" autocomplete="off" id="pre-booking-form-1" hidden>
        <input id="proceed-form-data" name="proceed-form-data" type="text" value="">
      </form>
  </div>
</body>
<script type="text/javascript">

  $(document).ready(function() {
    let currentGateway = 'none';
    let jsonArrayString_post_params = $('#post-params').val();
    let post_params = JSON.parse(jsonArrayString_post_params);

    //changes start of addition of convenience fees 1
    let jsonConvDetails = $('#conv').val();

    let convDetails = JSON.parse(jsonConvDetails);

    let newArray = [];
    let convenience_fee = 0;
    let esewaConvinienceFee = 0;
    let cipsConvinienceFee = 0;
    let khaltiConvinienceFee = 0;
    let fonepayConvinienceFee = 0;

    // changes new variable for nica convenience_fee
    let nicaConvinienceFee = 0;
    let pgConvTotalAmount = 0;
    //changes end of addition of convenience fees 1 

    //changes Start of modification of code for consideration of reward points
    let total_amount_val = post_params['amount'];
    post_params['currency'] = 'NPR';

    // console.log(isRewardPoints);
    //changes  End of modification of code for consideration of reward points
    // changes removed for consideration of reward points
    // let total_amount_val = parseFloat(post_params['total_amount_val']);
    // changes removed for consideration of reward points
    // let discount = parseFloat(post_params['promocode_discount']);
    let total_payable_amount = total_amount_val;
    //changes start for convenience fee 2
    let newAmount = 0;
            function displayError(message) {
            $('.error-message').text(message).show();
        }

        // Function to hide error message
        function hideError() {
            $('.error-message').hide();
        }

        // Function to validate amount field
        function validateAmount(amount) {
            if (isNaN(amount) || amount < 50 || amount > 1000000) {
                displayError('Amount must be a number between 50 and 1,000,000.');
                return false;
            }
            hideError();
            return true;
        }
    $("#amount").change(function() {
      let jsonConvDetails = $('#conv').val();
      let convDetails = JSON.parse(jsonConvDetails);
      let newArray = [];
      let convenience_fee = 0;
      let esewaConvinienceFee = 0;
      let cipsConvinienceFee = 0;
      let khaltiConvinienceFee = 0;
      let fonepayConvinienceFee = 0;
      newAmount = $('#amount').val() ?? 0;
      newAmount = parseFloat(newAmount) ? parseFloat(newAmount):0;
      $('#total-fare').text(post_params['currency'] + ' ' + (newAmount));
      if (convDetails.length > 0 && convDetails != undefined) {
        convDetails.forEach(convDetail => {

          newArray[`${convDetail['data'][0]['module']}`] = convDetail['data'][0];
        });
        for (let k in newArray) {
          if (newArray[k]['value_type'] == 'percentage') {
            newArray[k]['value'] = (newArray[k]['value'] / 100) * newAmount;

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
            nicaConvinienceFee = parseFloat(newArray[k]['value']) ?? 0;;
          }

        }
        switch (currentGateway) {
          case 'esewa':
            $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + esewaConvinienceFee));
            $('#total-payable-amount').text(post_params['currency'] + ' ' + (newAmount + esewaConvinienceFee));
            gatewayRenamed = 'eSewa';
            post_params['payment_method'] = $('#pay-method-pay-with-esewa').val();
            currentGateway = 'esewa';
            break;
          case 'khalti':
            $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + khaltiConvinienceFee));
            $('#total-payable-amount').text(post_params['currency'] + ' ' + (khaltiConvinienceFee + newAmount));
            gatewayRenamed = 'Khalti';
            post_params['payment_method'] = $('#pay-method-pay-with-khalti').val();
            currentGateway = 'khalti';

            break;
          case 'fonepay':
            $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + fonepayConvinienceFee));
            $('#total-payable-amount').text(post_params['currency'] + ' ' + (newAmount + fonepayConvinienceFee));
            gatewayRenamed = 'FonePay';
            post_params['payment_method'] = $('#pay-method-pay-now').val();
            currentGateway = 'fonepay';

            break;
          case 'cips':
            $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + cipsConvinienceFee));
            $('#total-payable-amount').text(post_params['currency'] + ' ' + (newAmount + cipsConvinienceFee));
            gatewayRenamed = 'ConnectIPS';
            post_params['payment_method'] = $('#pay-method-pay-at-bank').val();
            currentGateway = 'cips';

            break;
            //changes added new case for nica
          case 'nica':
            $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + nicaConvinienceFee));
            $('#total-payable-amount').text(post_params['currency'] + ' ' + (newAmount + nicaConvinienceFee));
            gatewayRenamed = 'NIC Asia';
            post_params['payment_method'] = $('#pay-method-pay-with-nica').val();
            currentGateway = 'nica';

            break;
          default:
          $('#total-payable-amount').text(post_params['currency'] + ' ' + (newAmount));
            break;

        }
      }



    });
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
          nicaConvinienceFee = parseFloat(newArray[k]['value']) ?? 0;;
        }

      }
    }
    //changes end for convenience fee 2
    $('#total-fare').text(post_params['currency'] + ' ' + total_amount_val);
    $('#convenience-fee').text(post_params['currency'] + ' ' + convenience_fee);
    // $('#discount').text(post_params['currency'] + ' ' + discount);
    $('#total-payable-amount').text(post_params['currency'] + ' ' + total_payable_amount);
    // $('#cashback').text(post_params['currency'] + ' ' + cashback);
    //changes added new nica field
    //add cips later
    let gateways = ['khalti', 'fonepay', 'esewa', 'nica','cips'];
    $.each(gateways, function(index, gateway) {
      $('.image-buttons').append(`<div class="image-button button-${gateway}"><a href="#"><img src="http://travelfreetravels.com/extras/system/template_list/template_v3/images/logos-payments/${gateway}.png" alt="${gateway}" style="width: 100px;"></a></div>`);

      $(document).on('click', '.button-' + gateway, function(e) {
        total_payable_amount = parseFloat(total_payable_amount);
        let jsonConvDetails = $('#conv').val();
        let convDetails = JSON.parse(jsonConvDetails);
        let newArray = [];
        let convenience_fee = 0;
        let esewaConvinienceFee = 0;
        let cipsConvinienceFee = 0;
        let khaltiConvinienceFee = 0;
        let fonepayConvinienceFee = 0;
        newAmount = $('#amount').val() ?? 0;
         newAmount = parseFloat(newAmount) ?parseFloat(newAmount) : 0;
        $('#total-fare').text(post_params['currency'] + ' ' + (newAmount));
        if (convDetails.length > 0 && convDetails != undefined) {
          convDetails.forEach(convDetail => {

            newArray[`${convDetail['data'][0]['module']}`] = convDetail['data'][0];
          });
          for (let k in newArray) {
            if (newArray[k]['value_type'] == 'percentage') {
              newArray[k]['value'] = (newArray[k]['value'] / 100) * newAmount;

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
              nicaConvinienceFee = parseFloat(newArray[k]['value']) ?? 0;;
            }

          }
        }
        e.preventDefault();
        $('.proceed-error').hide();
        $('#proceed-bool').val(1);
        //changes added first two lines in each of the following cases
        switch (gateway) {
          case 'esewa':

            $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + esewaConvinienceFee));
            $('#total-payable-amount').text(post_params['currency'] + ' ' + (newAmount + esewaConvinienceFee));
            gatewayRenamed = 'eSewa';
            post_params['payment_method'] = $('#pay-method-pay-with-esewa').val();
            currentGateway = 'esewa';
            break;
          case 'khalti':
            $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + khaltiConvinienceFee));
            $('#total-payable-amount').text(post_params['currency'] + ' ' + (khaltiConvinienceFee + newAmount));
            gatewayRenamed = 'Khalti';
            post_params['payment_method'] = $('#pay-method-pay-with-khalti').val();
            currentGateway = 'khalti';

            break;
          case 'fonepay':
            $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + fonepayConvinienceFee));
            $('#total-payable-amount').text(post_params['currency'] + ' ' + (newAmount + fonepayConvinienceFee));
            gatewayRenamed = 'FonePay';
            post_params['payment_method'] = $('#pay-method-pay-now').val();
            currentGateway = 'fonepay';

            break;
          case 'cips':
            $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + cipsConvinienceFee));
            $('#total-payable-amount').text(post_params['currency'] + ' ' + (newAmount + cipsConvinienceFee));
            gatewayRenamed = 'ConnectIPS';
            post_params['payment_method'] = $('#pay-method-pay-at-bank').val();
            currentGateway = 'cips';

            break;
            //changes added new case for nica
          case 'nica':
            $('#convenience-fee').text(post_params['currency'] + ' ' + (convenience_fee + nicaConvinienceFee));
            $('#total-payable-amount').text(post_params['currency'] + ' ' + (newAmount + nicaConvinienceFee));
            gatewayRenamed = 'NIC Asia';
            post_params['payment_method'] = $('#pay-method-pay-with-nica').val();
            currentGateway = 'nica';

            break;
          default:
            break;
        }
        $('.selected-gateway-image').html("");
        $('.selected-gateway-image').html(`<img src='http://travelfreetravels.com/extras/system/template_list/template_v3/images/logos-payments/${gateway}.png' alt=${gatewayRenamed} style="width: 100%;">`);
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
        //changes start for convenience fees 3
        let convFeeText = $('#convenience-fee').text();
        let convFeeArray = convFeeText.split(" ");
        let convFee = Number(convFeeArray[1]);
        post_params['pg_convenience'] = convFee;
        post_params['amount'] = newAmount;
        //changes end for convenience fees 3  
        let jsonArrayString_post_params_1 = JSON.stringify(post_params);
        $('#proceed-form-data').val(jsonArrayString_post_params_1);
        amount = parseFloat($('#amount').val());
            if (validateAmount(amount)) {
        $('#pre-booking-form-1').submit();
            }
      }
    });
            $('#amount').focus(function() {
            hideError();
        });

        // Event listener for amount input keydown
        $('#amount').keydown(function() {
            hideError();
        });
  });
</script>
<script>

</script>