<?php
$autoSubmission = true;
$AMT = $pay_data['amount'];
// $AMT = 10;
$R1 = $pay_data['txnid'];
// $this->session->set_userdata('esewa_prn', $R1);
$GLOBALS['CI']->session->set_userdata('esewa_prn', $R1);
$RU =     base_url() . 'index.php/payment_gateway/verify_esewa';
$RU_cancel =     base_url() . 'index.php/payment_gateway/verify_esewa';
$sharedSecretKey = $pay_data['sharedSecretKey'];
$secretKey = $pay_data['secretKey'];
$message = "total_amount=$AMT,transaction_uuid=$R1,product_code=$sharedSecretKey";
$s = hash_hmac('sha256', $message, $secretKey, true);
$sign = base64_encode($s);
$paymentDevUrl = $pay_data['pay_target_url'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Esewa Payment page</title>
</head>

<body>
    <form action="<?php echo $paymentDevUrl; ?>" method="POST" id="payment-form" hidden>
        <input type="text" id="amount" name="amount" value="<?php echo $AMT; ?>" required readonly>
        <input type="text" id="tax_amount" name="tax_amount" value="0" required readonly>
        <input type="text" id="total_amount" name="total_amount" value="<?php echo $AMT; ?>" required readonly>
        <input type="text" id="transaction_uuid" name="transaction_uuid" value="<?php echo $R1; ?>" required readonly>
        <input type="text" id="product_code" name="product_code" value="<?php echo $sharedSecretKey; ?>" required readonly> //merchant code
        <input type="text" id="product_service_charge" name="product_service_charge" value="0" required readonly>
        <input type="text" id="product_delivery_charge" name="product_delivery_charge" value="0" required readonly>
        <input type="text" id="success_url" name="success_url" value="<?php echo $RU; ?>" required readonly>
        <input type="text" id="failure_url" name="failure_url" value="<?php echo $RU_cancel; ?>" required readonly>
        <input type="text" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required readonly>
        <input type="text" id="signature" name="signature" value="<?php echo $sign; ?>" required readonly>
        <!-- <input value=" Submit" type="submit"> -->
    </form>
</body>

</html>

<?php if ($autoSubmission == true) : ?>
    <script>
        window.onload = function() {
            window.setTimeout(function() {
                document.getElementById("payment-form").submit();
            }, 0);
        };
    </script>
<?php endif; ?>