<?php
$sms_message = '';
$sms_message .= 'Hotel Booking is in HOLD status for the client: '.$domain_name;
$sms_message .= ', AppReference: '.$booking_transaction_details['app_reference'];
$sms_message .= ' ,check mail for more details.';
echo $sms_message;
?>
