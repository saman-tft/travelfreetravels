<?php 


$Merchantid= '582';
$Appid ='MER-582-APP-1';
$AppName= 'Travel Free Travels';
$txn_id='tft'.rand('11111','9999999');


 ?>

<form action="baseurl/connectipswebgw/loginpage" method="post">
 <br>
 MERCHANT ID
 <input type="text" name="MERCHANTID" id="MERCHANTID" value="<?php echo  $Merchantid ?>"/>
 <br>
 APP ID
 <input type="text" name="APPID" id="APPID" value="<?php echo $Appid ?>"/>
 <br>
 APP NAME
 <input type="text" name="APPNAME" id="APPNAME" value="<?php echo $AppName ?>"/>
 <br>
 TXN ID
 <input type="text" name="TXNID" id="TXNID" value="<?php echo $txn_id ?>"/>
 <br> 
connectIPS Process & Interface Document For Merchant Public

 Page 7 of 12
 TXN DATE
 <input type="text" name="TXNDATE" id="TXNDATE" value="15-03-2022"/> <br>
TXN CRNCY
<input type="text" name="TXNCRNCY" id="TXNCRNCY" value="NPR"/> <br>
 TXN AMT
 <input type="text" name="TXNAMT" id="TXNAMT" value="500"/>
 <br>
 REFERENCE ID
 <input type="text" name="REFERENCEID" id="REFERENCEID" value="REF-001"/>
 <br>
 REMARKS
 <input type="text" name="REMARKS" id="REMARKS" value="RMKS-001"/>
 <br>
 PARTICULARS
 <input type="text" name="PARTICULARS" id="PARTICULARS" value="PART-001"/>
 <br>
 TOKEN
<input type="text" name="TOKEN" id="TOKEN" value=
“fRLMniZSmpKs/FrO7w53NmlIiXKX1+AQdhJUgBO51S+Ho9ZzYOICghA5kW3hS/B1nf2EY5zziutx
GejSBQ
8NFgQo7MWYi/QPnSZ6jByI1gzRnx73/EUZmG9tRgRdDq2Zs99Y8m4by2uEQo0ZldbTHmO4kRui
fUTSur Fn+zdbprg=”
 <br>
 <input type="submit" value="Submit"> </form>