<?php
//echo "paypal view<pre/>";print_r($pay_data);die;
extract($pay_data);
session_start();
$paypal_url=$pay_target_url; // Test Paypal API URL
$paypal_id=$client_email; // Business email ID
?>

<body onload="do_submit();">
<form action='<?php echo $paypal_url; ?>' method='post'  name="payment_form">
<input type='hidden' name='business' value='<?php echo $paypal_id; ?>'>
<input type='hidden' name='cmd' value='_xclick'>
<input type='hidden' name='item_name' value='<?php echo META_ACCOMODATION_COURSE; ?>'>
<input type='hidden' name='item_number' value='<?php echo $txnid; ?>'>
<input type='hidden' name='amount' value='<?php echo $amount; ?>'>
<input type='hidden' name='no_shipping' value='1'>
<input type='hidden' name='currency_code' value='USD'>
<input type='hidden' name='cancel_return' value="<?php echo $furl ; ?>">
<input type='hidden' name='return' value="<?php echo $surl ; ?>">
<input name = "upload" value = "1" type = "hidden">
<input name = "no_note" value = "0" type = "hidden">
<input name = "bn" value = "PP-BuyNowBF" type = "hidden">
<input name = "tax" value = "0" type = "hidden">
<input name = "rm" value = "2" type = "hidden">
<input name = "handling_cart" value = "0" type = "hidden">
<input name = "lc" value = "GB" type = "hidden">
<input name = "cbt" value = "Return to <?php echo PROJECT_NAME;?>" type = "hidden">
<input name = "custom" value = "" type = "hidden">
</form> 
<script type="text/javascript">
      function do_submit() {
        document.payment_form.submit();
      }
    </script>
</body>
