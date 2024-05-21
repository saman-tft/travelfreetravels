<?php
$autoSubmission = true;
$paymentDevUrl = $pay_data['url'];
$accessKey = $pay_data['accessKey'];
$profileId = $pay_data['profileId'];
$uuid = $pay_data['uuid'];
$signedDateTime = $pay_data['signedDateTime'];
$locale = $pay_data['locale'];
$amount = $pay_data['amount'];
$firstName = $pay_data['firstName'];
$lastName = $pay_data['lastName'];
$email = $pay_data['email'];
$phone = $pay_data['phone'];
$addressLine1 = $pay_data['addressLine1'];
$addressCity = $pay_data['addressCity'];
$addressState = $pay_data['addressState'];
$addressCountry = $pay_data['addressCountry'];
$addressPostalCode = $pay_data['addressPostalCode'];
$transactionType = $pay_data['transactionType'];
$refNumber = $pay_data['refNumber'];
$currency = $pay_data['currency'];
$paymentMethod = $pay_data['paymentMethod'];
$cardType = $pay_data['cardType'];
$cardNumber = $pay_data['cardNumber'];
$cardExpiryDate = $pay_data['cardExpiryDate'];
$signature = $pay_data['signature'];

$signed_field_names = $pay_data['signed_field_names'];
$unsigned_field_names = $pay_data['unsigned_field_names'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>NICASIA Card Payment page</title>
</head>

<body>
    <form id="frm-nicasia" action="<?php echo $paymentDevUrl; ?>" method="post" hidden>
        <div class="form-group">
            <label for="">access_key</label><br />
            <input size="50" type="hidden" name="access_key" value="<?php echo $accessKey; ?>" readonly><br /><br />
            <label for="">profile_id</label><br />
            <input size="50" type="hidden" name="profile_id" value="<?php echo $profileId; ?>" readonly><br /><br />
            <label for="">transaction_uuid</label><br />
            <input size="50" type="hidden" name="transaction_uuid" value="<?php echo $uuid; ?>" readonly><br /><br />
            <label for="">signed_field_names</label><br />
            <input size="50" type="hidden" name="signed_field_names" value="<?php echo $signed_field_names; ?>" readonly><br /><br />
            <label for="">unsigned_field_names</label><br />
            <input size="50" type="hidden" name="unsigned_field_names" value="<?php echo $unsigned_field_names; ?>" readonly><br /><br />
            <label for="">signed_date_time</label><br />
            <input size="50" type="hidden" name="signed_date_time" value="<?php echo $signedDateTime; ?>" readonly><br /><br />
            <label for="">locale</label><br />
            <input size="50" type="hidden" name="locale" value="<?php echo $locale; ?>" readonly><br /><br />
            <label for="">auth_trans_ref_no</label><br />
            <input size="50" type="hidden" name="auth_trans_ref_no" readonly><br /><br />
            <label for="">amount</label><br />
            <input size="50" type="hidden" name="amount" value="<?php echo $amount; ?>" readonly><br /><br />
            <label for="">bill_to_forename</label><br />
            <input size="50" type="hidden" name="bill_to_forename" value="<?php echo $firstName; ?>" readonly><br /><br />
            <label for="">bill_to_surname</label><br />
            <input size="50" type="hidden" name="bill_to_surname" value="<?php echo $lastName; ?>" readonly><br /><br />
            <label for="">bill_to_email</label><br />
            <input size="50" type="hidden" name="bill_to_email" value="<?php echo $email; ?>" readonly><br /><br />
            <label for="">bill_to_phone</label><br />
            <input size="50" type="hidden" name="bill_to_phone" value="<?php echo $phone; ?>" readonly><br /><br />
            <label for="">bill_to_address_line1</label><br />
            <input size="50" type="hidden" name="bill_to_address_line1" value="<?php echo $addressLine1; ?>" readonly><br /><br />
            <label for="">bill_to_address_city</label><br />
            <input size="50" type="hidden" name="bill_to_address_city" value="<?php echo $addressCity; ?>" readonly><br /><br />
            <label for="">bill_to_address_state</label><br />
            <input size="50" type="hidden" name="bill_to_address_state" value="<?php echo $addressState; ?>" readonly><br /><br />
            <label for="">bill_to_address_country</label><br />
            <input size="50" type="hidden" name="bill_to_address_country" value="<?php echo $addressCountry; ?>" readonly><br /><br />
            <label for="">bill_to_address_postal_code</label><br />
            <input size="50" type="hidden" name="bill_to_address_postal_code" value="<?php echo $addressPostalCode; ?>" readonly><br /><br />
            <label for="">transaction_type</label><br />
            <input size="50" type="hidden" name="transaction_type" value="<?php echo $transactionType; ?>" readonly><br /><br />
            <label for="">reference_number</label><br />
            <input size="50" type="hidden" name="reference_number" value="<?php echo $refNumber; ?>" readonly><br /><br />
            <label for="">currency</label><br />
            <input size="50" type="hidden" name="currency" value="<?php echo $currency; ?>" readonly><br /><br />
            <label for="">payment_method</label><br />
            <input size="50" type="hidden" name="payment_method" value="<?php echo $paymentMethod; ?>" readonly><br /><br />
            <label for="">signature</label><br />
            <input size="50" type="hidden" name="signature" value="<?php echo $signature; ?>" readonly><br /><br />
            <label for="">card_type</label><br />
            <input size="50" type="hidden" name="card_type" value="<?php echo $cardType; ?>" readonly><br /><br />
            <label for="">card_number</label><br />
            <input size="50" type="hidden" name="card_number" value="<?php echo $cardNumber; ?>" readonly><br /><br />
            <label for="">card_expiry_date</label><br />
            <input size="50" type="hidden" name="card_expiry_date" value="<?php echo $cardExpiryDate; ?>" readonly><br /><br />
  
        </div>
    </form>
</body>

</html>

<?php if ($autoSubmission == true) : ?>
    <script>
        window.onload = function() {
            window.setTimeout(function() {
                document.getElementById("frm-nicasia").submit();
            }, 0);
        };
    </script>
<?php endif; ?>