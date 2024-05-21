 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<?php 
//echo "Please contact admin for the booking Processs..";die;
// debug($pay_data['txnid']);die;
//  $merchant_id = intval(582);
//  $appid = "MER-582-APP-1";
//  $appname = "Travel Free Travels";
//  $txid = (string)$pay_data["txnid"];
//  $txndate = (string)$pay_data["txndate"];
//  $txncrncy = (string)$pay_data["currency"];
//  $txtamount = intval($pay_data["amount"]);
//  $referenceid = (string)$pay_data["refid"];
//  $remarks = (string)$pay_data["remarks"];
//  $particulars = (string)$pay_data["particulars"];
//  $token = (string)$pay_data["token"];
  $merchant_id = 582;
 $appid = "MER-582-APP-1";
 $appname = "Travel Free Travels";
 $txid = (string)"TRANS-88828";
 $txndate = (string)"17-10-2023";
 $txncrncy = (string)"NPR";
 $txtamount = 500;
 $referenceid = (string)$pay_data['txnid'];
 $remarks = (string)"RMKS-001";
 $particulars = (string)"PART-001";
 $string = "MERCHANTID=$merchant_id,APPID=$appid,APPNAME=$appname,TXNID=$txid,TXNDATE=$txndate,TXNCRNCY=$txncrncy,TXNAMT=$txtamount,REFERENCEID=$referenceid,REMARKS=$remarks,PARTICULARS=$particulars,TOKEN=TOKEN";
 //debug($string);

		


        $messageDigest = hash('sha256', $string);///1
		$privateKeyPath = 'https://travelfreetravels.com/b2c/views/template_list/template_v1/payment/Connect/CREDITOR.pfx';
		$fileData = openssl_pkcs12_read(file_get_contents($privateKeyPath), $certs, '123');
        //debug($certs);die;
	       $private_key='-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBANXlpCDxR6sYdf3s
A6VvCEAwFC6/z5Xb2vKCXqRwEYEtgkE7s1/CZT5hqBkLleQIKN9DKoPCuNYkQkhy
QWZF0TG8xkRkk67TJo6+dMcC0lvkwc8/bjbpeBep2nF4kIHdYdgE9NUk6gi/4UFd
J3QDVgEDrhn7Peri0jO993D+YpjHAgMBAAECgYBaKHmOCSWUULMrXJgM20g3Bgz7
x43QNOOM5LbPyQ0Xzf7hUDDDZEUYjkE0jVWY0Hep472/3Avc91uY/c/jM/qAnsTN
Buu/yepNSNXQsVnp2mhF5JMeHoQjO9B5gfQePmTUB7KiLg8ZzSi61BcGguWGRYtX
M0tCoePWDOCc65tfwQJBAPoW2IwdtlSclGMCICfAVjAVH1JTAOqcDJ4FQQ3UGcny
jsSSEpOl54sdv+9GnJK2OA2odMRoQnX+qaNJxxSnVkMCQQDa89DcmvpLliTiM+qg
QSUGueGN1DxIPFUvYoWUWYEJi7ua69MYCbW9c5Wf1JWpTXsDWh6Eb0q9k8ft1kZh
UGUtAkEAm4tECfmc4ok0fVPgHfkxYdxxS6mWY1TFQC8yY+BsXb8/7qCPb0d7eHn7
W13GmjU9Lbl9Tn8t/udyKL4FVSIyswJAQMNnnyk0KdFyfXovx1Edm5y1y9bgMdmu
tMJmkpfa5DDxwARLP0v39t7OfiVKU3a4kShB6JhmuiaRN5du3/AAzQJBAKyKH7Ly
pzZ40L206oPGDkwHWBDLBHWK0RwTP8976RMBGRUfBnGLeQVaZr4QQWAa0pT3EEzZ
a93nWeW6gxu/1dM=
-----END PRIVATE KEY-----';
		if (!$fileData) {
			die("Unable to load private key");
		}
		else{
			$private_key = openssl_pkey_get_private($certs['pkey']);
           // debug($private_key );die;
			openssl_sign($messageDigest, $signature, $private_key, 'sha256WithRSAEncryption');
			 debug($signature);die;
				$hash = base64_encode($signature);
				// debug($hash);die;


		}

   // debug($hash);die;



 $token = $hash;
debug( $token);die;

?>

<form action="https://uat.connectips.com:7443/connectipswebgw/loginpage" method="post" id="checkout_form">
    <label>MERCHANT ID</label>
    <input type="text" name="MERCHANTID" id="MERCHANTID" value="582"/>
    <label>APP ID</label>
    <input type="text" name="APPID" id="APPID" value="<?php echo $appid ?>"/>
    <label>APP NAME</label>
    <input type="text" name="APPNAME" id="APPNAME" value="<?php echo $appname ?>"/>
    <label>TXN ID</label>
    <input type="text" name="TXNID" id="TXNID" value="$txid"/>
    <label>TXN DATE</label>
    <input type="text" name="TXNDATE" id="TXNDATE" value="1-10-2023"/>
    <label>TXN CRNCY</label>
    <input type="text" name="TXNCRNCY" id="TXNCRNCY" value="NPR"/>
    <label>TXN AMT</label>
    <input type="text" name="TXNAMT" id="TXNAMT" value="<?php echo $txtamount ?>"/>
    <label>REFERENCE ID</label>
    <input type="text" name="REFERENCEID" id="REFERENCEID" value="<?php $referenceid ?>"/>
    <label>REMARKS</label>
    <input type="text" name="REMARKS" id="REMARKS" value="RMKS-001"/>
    <label>PARTICULARS</label>
    <input type="text" name="PARTICULARS" id="PARTICULARS" value="PART-001"/>
    <label>TOKEN</label>
    <input type="text" name="TOKEN" id="TOKEN" value="<?php echo $token; ?>"/>
    <input type="submit" value="Submit">
</form>
<script>
	 $(document).ready(function(){
    $('#checkout_form').submit();
    
    setTimeout(function(){
      
    },500);

  });
</script>

<!-- 
<?php 
$url = "https://uat.connectips.com:7443/connectipswebgw/loginpage";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST,true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);


$headers = array(
  "Content-Type : application/x-www-form-urlencoded",
);
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

$data = "MERCHANTID=$merchantid&APPID=$appid&APPNAME='Travel Free Travels'&TXNID=$txid&TXNDATE=$txndate&TXNCRNCY=$txncrncy&TXNAMT=$txtamount&REFERENCEID=$referenceid&REMARKS=$remarks&PARTICULARS=$particulars&TOKEN=$token";


curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$result=curl_exec ($ch);
?> -->