<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Notification</title>
</head>

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
      <td style="padding:0px;">
      <table width="100%">
         <tr>
            <td>
             <a href="<?= base_url() ?>index.php/?>" target="_blank"><img alt="" border="0" height="50" src="<?php echo $img_url.PROJECT_FOLDER.'/extras/custom/'.CURRENT_DOMAIN_KEY.'/images/logo.png';?>" style="display:block; border:none; outline:none; text-decoration:none;"></a>
            </td>
         </tr>
          <tr>
                                <td>
                                 <ul class="exploreall" style=" padding: 0px;text-align: center;">
                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo base_url();?>index.php/general/index/flights?default_view=VHCID1420613784"><strong>Flights</strong></a>
                                    </li>


                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo base_url();?>index.php/general/index/hotels?default_view=VHCID1420613748"><span class="sprte cmnexplor"></span> <strong>Hotels</strong></a>
                                    </li>


                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo base_url();?>index.php/general/index/buses?default_view=VHCID1433498307"><span class="sprte cmnexplor"></span> <strong>Buses</strong></a>
                                    </li>


                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo base_url();?>index.php/general/index/transfers?default_view=TMVIATID1527240212"><span class="sprte cmnexplor"></span> <strong>Transfers</strong></a>
                                    </li>


                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo base_url();?>index.php/general/index/activities?default_view=TMCID1524458882"><span class="sprte cmnexplor"></span> <strong>Activities</strong></a>
                                    </li>


                                    <li style="display:inline-block;list-style: none;margin-left: 10px">
                                      <a style="text-decoration: underline;" href="<?php echo base_url();?>index.php/general/index/holidays?default_view=VHCID1433498322"><span class="sprte cmnexplor"></span> <strong>Holidays</strong></a>
                                    </li>
                                  </ul>
                                </td>
                              </tr>
                                <tr>
                                <td>
                                  <div class="cover_img" style="">
                                  <!--email.jpeg-->
                                    <img src="<?=$_SERVER['HTTP_HOST'].DOMAIN_IMAGE_DIR."email.jpeg";?>" alt="title" border="0" class="mail_img" height="252"  style="display:block" width="100%">
           
                                  </div>
                                </td>
                              </tr>
         <tr>
            <td style="font-size: 15px;padding: 0 0px; text-align:left;line-height:1.4;font-family:Arial,Helvetica,sans-serif;color:#656565;"><!--<h1 style="margin: 25px 0px;">Welcome to Book my Holidays</h1>-->
<p class="" style="color:#666;margin:10px 10px;">Hello Admin,</strong>
</p>
<p class="" style="color:#666;margin:0 10px;"> Please enter the OTP to Login Admin Dashboard:- <?=$OTP?>

</p>
<br/>
<table width="100%" style="border: 1px solid #eaeaea; font-size: 13px; color: #656565; font-family: arial;" cellpadding="5">
   
   <tr>
      <td><strong>Username</strong></td>
      <td><?php echo $email;?></td>
   </tr>

</table>

<br/>


            </td>
         </tr>
                  <tr>
        <td>
          <table align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth2" width="100%">
            <tbody>
              <tr>
                <td style="padding:0 10px 0px;  border-top:none" width="100%">
                  <table align="center" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="devicewidth" width="100%">
                    <tbody>
                      <tr>
                        <td>
                          <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                              <td align="center" style="color: #666;font-family: arial;font-size: 11px;padding:10px 10px 10px;text-align: center;" valign="middle">
                                <a href="%3C?=@base_url().'index.php/'?%3E" style="text-decoration: underline; color: #333"><?=@domain_name()?></a> &#x24B8; copyright <?php $date=intval(date("Y")); echo $date?> - <?php echo $date+1;?>
                              </td>
                            </tr>
                          </table>


                         
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>

            </tbody>
          </table>
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
