
<?php  
//echo "Please contact admin for the booking Process...";die;
    ini_set('display_errors', '1');
    date_default_timezone_set("Asia/Kathmandu");	
    function generateHash($string) {
        // Try to locate certificate file
        if (!$cert_store = file_get_contents("https://travelfreetravels.com/b2c/views/template_list/template_v1/payment/Connect/TRAVELFREE.pfx")) {
        	echo "Error: Unable to read the cert file\n";
        	exit;
        }
        
        // Try to read certificate file
        if (openssl_pkcs12_read($cert_store, $cert_info, "TravelFRee@1")) {
        	if($private_key = openssl_pkey_get_private($cert_info['pkey'])){
        		$array = openssl_pkey_get_details($private_key);
        	    // print_r($array);
        	}
        } else {
        	echo "Error: Unable to read the cert store.\n";
        	exit;
        }
        $hash = "";
        if(openssl_sign($string, $signature , $private_key, "sha256WithRSAEncryption")){
	        $hash = base64_encode($signature);
	        openssl_free_key($private_key);
        } else {
            echo "Error: Unable openssl_sign";
            exit;
        }    
        return $hash;
    }

   // $amount_inPaisa= 500;  //sending amount in paisa
   $amount_inPaisa= $pay_data['amount']*100;  //sending amount in paisa
     $merchant_id = 199;
 $appid = "MER-199-APP-4";
 $appname = "Travel Free Travels";
 $random_number = rand(100000, 100000000);
 $txid = $pay_data['txnid'];
 $txndate =date('d-m-Y', time());
 $txncrncy = "NPR";
 $txtamount = $amount_inPaisa;
 $referenceid = $pay_data['txnid'];
 $remarks = $productin;
 $particulars = $remarks;
    
    $string = "MERCHANTID=$merchant_id,APPID=$appid,APPNAME=$appname,TXNID=$txid,TXNDATE=$txndate,TXNCRNCY=$txncrncy,TXNAMT=$txtamount,REFERENCEID=$referenceid,REMARKS=$remarks,PARTICULARS=$particulars,TOKEN=TOKEN";


    $token = generateHash($string);
 ?>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <form action="https://login.connectips.com/connectipswebgw/loginpage" method="post" id="checkout_form">
    <input type="hidden" name="MERCHANTID" id="MERCHANTID" value="<?=$merchant_id?>"/>
    <input type="hidden" name="APPID" id="APPID" value="<?php echo $appid ?>"/>
    <input type="hidden" name="APPNAME" id="APPNAME" value="<?php echo $appname ?>"/>
    <input type="hidden" name="TXNID" id="TXNID" value="<?php echo $txid ?>"/>
    <input type="hidden" name="TXNDATE" id="TXNDATE" value="<?php echo $txndate ?>"/>
    <input type="hidden" name="TXNCRNCY" id="TXNCRNCY" value="NPR"/>
    <input type="hidden" name="TXNAMT" id="TXNAMT" value="<?php echo $txtamount ?>"/>
    <input type="hidden" name="REFERENCEID" id="REFERENCEID" value="<?php echo  $referenceid ?>"/>
    <input type="hidden" name="REMARKS" id="REMARKS" value="<?php echo $remarks ?>"/>
    <input type="hidden" name="PARTICULARS" id="PARTICULARS" value="<?php echo $remarks ?>"/>
    <input type="hidden" name="TOKEN" id="TOKEN" value="<?php echo $token; ?>"/>
  <!--   <input type="submit" value="Submit"> -->
</form>
<script>
     $(document).ready(function(){
    $('#checkout_form').submit();
    
    /*setTimeout(function(){
      
    },500);*/

  });
</script>