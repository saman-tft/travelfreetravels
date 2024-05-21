<?php
 
// debug($currency);
// exit;
$autoSubmission = true;
$MD = 'P';
$AMT = $pay_data['amount'];
//$AMT = 15;
$CRN = $currency;
$DT = date('m/d/Y');
$R1 = $pay_data['txnid'];
$R2 = 'Payment';
//$RU = 'http://localhost/verify.php'; //fully valid verification page link
$RU = 	base_url().'index.php/payment_gateway/verify_fonepay';
$PRN = $pay_data['txnid'];
$PID = $pay_data['PID'];
$sharedSecretKey = $pay_data['sharedSecretKey'];
$DV = hash_hmac('sha512',$PID.','.$MD.','.$PRN.','.$AMT.','.$CRN.','.$DT.','.$R1.','.$R2.','.$RU, $sharedSecretKey);
//$paymentLiveUrl = 'https://clientapi.fonepay.com/api/merchantRequest';
$paymentDevUrl = $pay_data['pay_target_url'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Fonepay Payment page</title>
</head>
<body>
<form method="GET" id ="payment-form" action="<?php echo $paymentDevUrl; ?>">
<input type="hidden" name="PID" value="<?php echo $PID; ?>" >
<input type="hidden" name="MD" value="<?php echo $MD; ?>">
<input type="hidden" name="AMT" value="<?php echo $AMT; ?>">
<input type="hidden" name="CRN" value="<?php echo $CRN; ?>">
<input type="hidden" name="DT" value="<?php echo $DT; ?>">
<input type="hidden" name="R1" value="<?php echo $R1; ?>">
<input type="hidden" name="R2" value="<?php echo $R2; ?>">
<input type="hidden" name="DV" value="<?php echo $DV; ?>">
<input type="hidden" name="RU" value="<?php echo $RU; ?>">
<input type="hidden" name="PRN" value="<?php echo $PRN; ?>">
<!-- <input type="submit" value="Click to Pay"> -->
</form>

</body>
</html>

<?php if ($autoSubmission ==
true): ?> <script> window.onload=function(){ window.setTimeout(function() {
document.getElementById("payment-form").submit(); }, 0);
};
</script>
<?php endif; ?>