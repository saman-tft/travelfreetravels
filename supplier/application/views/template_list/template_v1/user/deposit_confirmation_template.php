<!DOCTYPE html>
<html>

<body>

<?php 
  $img_url = str_replace(PROJECT_FOLDER.'/supervision','',base_url());
?>


<table width="100%">
   <tr>
      <td>
<table width="80%" border="0" style="border-collapse: collapse; border: 1px solid #dddddd; box-shadow:0px 0px 13px #dddddd;" cellspacing="0" cellpadding="0" align="center">
<tbody>
   <tr>
      <td style="padding: 15px;">
      <table width="100%">
           <tr>
            <td align="center" height="50" width="140">
            <div class="imgpop" style="float: left;padding: 20px 0 10px 0px;">
            <a href="<?= get_base_url() ?>" target="_blank"><img alt="Vivance Travels" border="0" height="50" src="<?php echo $img_url.PROJECT_FOLDER.'/extras/custom/'.CURRENT_DOMAIN_KEY.'/images/logo.png';?>" style="display:block; border:none; outline:none; text-decoration:none;"></a>
            </div>
            </td>
         </tr>
<tr>
                                <td>
                                  <ul class="exploreall" style=" padding: 0px;text-align: center;">
                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo get_base_url();?>index.php/general/index/flights?default_view=VHCID1420613784"><strong>Flights</strong></a>
                                    </li>


                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo get_base_url();?>index.php/general/index/hotels?default_view=VHCID1420613748"><span class="sprte cmnexplor"></span> <strong>Hotels</strong></a>
                                    </li>


                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo get_base_url();?>index.php/general/index/buses?default_view=VHCID1433498307"><span class="sprte cmnexplor"></span> <strong>Buses</strong></a>
                                    </li>


                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo get_base_url();?>index.php/general/index/transfers?default_view=TMVIATID1527240212"><span class="sprte cmnexplor"></span> <strong>Transfers</strong></a>
                                    </li>


                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo get_base_url();?>index.php/general/index/activities?default_view=TMCID1524458882"><span class="sprte cmnexplor"></span> <strong>Activities</strong></a>
                                    </li>


                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo get_base_url();?>index.php/general/index/holidays?default_view=VHCID1433498322"><span class="sprte cmnexplor"></span> <strong>Holidays</strong></a>
                                    </li>
                                  </ul>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="cover_img" style="">
                                  
                                    <img src="<?=$_SERVER['HTTP_HOST'].DOMAIN_IMAGE_DIR."email.jpeg";?>" alt="title" border="0" class="mail_img" height="252"  style="display:block" width="100%">
           
                                  </div>
                                </td>
                              </tr>
         <tr>
            <td style="font-size: 15px; text-align:left;line-height:1.4;font-family:Arial,Helvetica,sans-serif;color:#656565;"><!--<h1 style="margin: 25px 0px;">Welcome to Book my Holidays</h1>-->
<br/>
Dear <strong><?php echo $master_transaction['request_user']; ?>,</strong>
<br/>
<?php 
$amount  = $master_transaction['amount'] * $master_transaction['currency_conversion_rate'];

?>
Your  balance request for a amount <?php echo $master_transaction['currency']; ?><?php echo $amount; ?>  has been processed on <?php echo app_friendly_absolute_date($master_transaction['updated_datetime']); ?>.

<br/>
<br/>
<br/>
<br/>
    </td>
         </tr>
         <tr><td></td></tr>
         <tr>
            <td>
              
            </td>
         </tr>
      </table>
      </td>
</tr>
</tbody>
</table>
</td>
   </tr>
</table>
</body>
</html>


