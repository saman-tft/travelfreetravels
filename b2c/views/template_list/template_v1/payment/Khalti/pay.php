<?php

// debug($pay_data);die;

// debug($currency);
// exit;
// $autoSubmission = true;
// $MD = 'P';
$AMT = round($pay_data['amount'], 2)*100; //amount should be given in paisa for khalti payment
$AMT = (int)$AMT;
// $AMT = 5000;
// debug($AMT);die;
$CRN = $currency;
// $DT = date('m/d/Y');
// $R1 = $pay_data['txnid'];
// $R2 = 'Payment';
$RU =     base_url() . 'index.php/payment_gateway/verify_khalti';
// $PRN = $pay_data['txnid'];
// $PID = $pay_data['PID'];
$sharedSecretKey = $pay_data['sharedSecretKey'];
$paymentDevUrl = $pay_data['pay_target_url'];
?>

<?php
$data = array(
    'return_url' => $RU,
    'website_url' => base_url(),
    'purchase_order_id' => $pay_data['txnid'], //appref
    'purchase_order_name' => $pay_data['txnid'], //Question-what to set here??
    'amount' => $AMT,
    'customer_info' => array(
        'name' => $pay_data['firstname'],
        'email' => $pay_data['email'],
        'phone' => $pay_data['phone']
    )
);
$curlopt_headers = array(
    'Authorization: key ' . $sharedSecretKey,
    'Content-Type: application/json',
);
$jsonData = json_encode($data);
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $paymentDevUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $jsonData,
    CURLOPT_HTTPHEADER => $curlopt_headers,
));

$response = curl_exec($curl);
curl_close($curl);
$response = json_decode($response, true);
// debug($data);die;
$pidx = $response['pidx'];
$payment_url = $response['payment_url'];
$expires_at = $response['expires_at'];
$expires_in = $response['expires_in'];
header("Location: $payment_url");
exit;
?>