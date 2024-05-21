<!DOCTYPE html>
<html>

<body>

<?php 
    $img_url = str_replace(PROJECT_FOLDER.'/supervision','',base_url());
 ?>


<table width="100%">
   <tr>
      <td>
<table width="645" border="0" style="border-collapse: collapse; border: 1px solid #dddddd; box-shadow:0px 0px 13px #dddddd;" cellspacing="0" cellpadding="0" align="center">
<tbody>
   <tr>
      <td style="padding:0px;">
      <table width="100%">
         <tr>
            <td>
                <a href="<?= get_base_url() ?>" target="_blank"><img alt="" border="0" height="50" src="<?php echo $img_url.PROJECT_FOLDER.'/extras/custom/'.CURRENT_DOMAIN_KEY.'/images/logo.png';?>" style="display:block; border:none; outline:none; text-decoration:none;"></a>
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
                                  <!--email.jpeg-->
                                    <img src="<?=$_SERVER['HTTP_HOST'].DOMAIN_IMAGE_DIR."email.jpeg";?>" alt="title" border="0" class="mail_img" height="252"  style="display:block" width="100%">
           
                                  </div>
                                </td>
                              </tr>

<tr>
                                <td align="center" height="50" style="color: #666;font-family: arial;font-size: 26px;font-weight: bold;line-height: 36px;padding: 10px 0 0; text-align: center;" valign="middle"><?=@domain_name()?>
                                !</td>
                              </tr>
         <tr>
            <td style="font-size: 15px; text-align:left;line-height:1.4;font-family:Arial,Helvetica,sans-serif;color:#656565;">
<p class="color:#666;" style="margin:0 10px">Dear <strong><?php echo $first_name.' '.$last_name; ?>,</strong></p>
<br/>

<p class="color:#666;" style="margin:0 10px">Your account has been deactivated.Please contact administrator for details.

</p>
<br>
            </td>
         </tr>

          <tr>
        <td style="border-top: 1px solid #ddd;">
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
              <!-- Spacing -->


             
              <!-- Spacing -->
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


