 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<?php 
$fname  = "./TRAVELFREETRAVELS.pfx";
//$handle = fopen($fname, "r") or die ("Unable to open file!");
//$p12buf = fread($handle, filesize($fname));
//fclose($handle);
 $pgurl='https://uat.connectips.com:7443/connectipswebgw/loginpage';
$merchant_id=NCHL_MERCHANT_ID;
$appid=NCHL_APP_ID;
$appname=NCHL_APP_NAME;
$txid=$pay_data['txnid'];
$txndate=date('d-m-Y');
$txncrncy=$currency;
$referenceid=$appref;
$remarks='LIVE';
$particulars='LIVE';
//$txtamount=number_format((float)$pay_data['amount'], 2, '.', '');

$txtamount=$pay_data['amount']*100;


     $string = "MERCHANTID=$merchant_id,APPID=$appid,APPNAME=$appname,TXNID=$txid,TXNDATE=$txndate,TXNCRNCY=$txncrncy,TXNAMT=$txtamount,REFERENCEID=$referenceid,REMARKS=$remarks,PARTICULARS=$remarks,TOKEN=TOKEN";
//echo $string;die;
//$result = openssl_pkcs12_read($p12buf,$cert_info,'123');
$data = file_get_contents('https://travelfreetravels.com//TRAVELFREETRAVELS.pfx');
$certPassword = 'your password';
$result=openssl_pkcs12_read($data,$cert_info,'3567');
//debug($certs);die;
if ($result) {
	 $private_key = openssl_pkey_get_private($cert_info['pkey']);
//debug($cert_info);die;
	 if (openssl_sign($string, $signature, $private_key, 'sha256WithRSAEncryption')) {
	//	 echo "sdfsdf";die;
            $hash = base64_encode($signature);
            openssl_free_key($private_key);
        }
//	echo "tsretser";
	//print_r($hash);die;
	$token=$hash;
//    print_r( $certs );
} else {
   // print_r( "Error!\r\n");
}
?>


<form action="https://login.connectips.com/connectipswebgw/loginpage" method="post" id="checkout_form">
    <label>MERCHANT ID</label>
    <input type="text" name="MERCHANTID" id="MERCHANTID" value="<?php echo $merchant_id; ?>"/>
    <label>APP ID</label>
    <input type="text" name="APPID" id="APPID" value="<?php echo $appid; ?>"/>
    <label>APP NAME</label>
    <input type="text" name="APPNAME" id="APPNAME" value="<?php echo $appname; ?>"/>
    <label>TXN ID</label>
    <input type="text" name="TXNID" id="TXNID" value="<?php echo $txid; ?>"/>
    <label>TXN DATE</label>
    <input type="text" name="TXNDATE" id="TXNDATE" value="<?php echo $txndate; ?>"/>
    <label>TXN CRNCY</label>
    <input type="text" name="TXNCRNCY" id="TXNCRNCY" value="<?php echo $txncrncy; ?>"/>
    <label>TXN AMT</label>
    <input type="text" name="TXNAMT" id="TXNAMT" value="<?php echo $txtamount; ?>"/>
    <label>REFERENCE ID</label>
    <input type="text" name="REFERENCEID" id="REFERENCEID" value="<?php echo $referenceid; ?>"/>
    <label>REMARKS</label>
    <input type="text" name="REMARKS" id="REMARKS" value="<?php echo $remarks; ?>"/>
    <label>PARTICULARS</label>
    <input type="text" name="PARTICULARS" id="PARTICULARS" value="<?php echo $remarks; ?>"/>
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