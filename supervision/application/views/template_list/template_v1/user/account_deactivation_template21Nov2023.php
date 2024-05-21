<!DOCTYPE html>
<html>

<body>




<table width="100%">
   <tr>
      <td>
<table width="80%" border="0" style="border-collapse: collapse; border: 1px solid #dddddd; box-shadow:0px 0px 13px #dddddd;" cellspacing="0" cellpadding="0" align="center">
<tbody>
   <tr>
      <td style="padding: 15px;">
      <table width="100%">
		    
         <tr>
            <td><img  src="https://www.travelfreetravels.com/extras/custom/TMX3644721637051232/images/TMX3644721637051232logo-loginpg.png"	 style="height:100px;" alt=" <?php echo domain_name();?>"></td>
         </tr>
         <tr>
            <td style="font-size: 15px; text-align:left;line-height:1.4;font-family:Arial,Helvetica,sans-serif;color:#656565;"><!--<h1 style="margin: 25px 0px;">Welcome to Book my Holidays</h1>-->
Dear <strong><?php echo $first_name.' '.$last_name; ?>,</strong>
<br/><br/>
<?php
 if($user_type == 3){
$text = 'B2B';
 }else{
$text = 'B2C';
 }
?>
Your <?php echo $text;?> account has been deactivated.Please contact your account manager for details.
<br/>For any other further assistance related to travel portal account do get in touch with our support team,
<a style="color:#656565;" target="_top" href="mailto:sales@travelfreetravel.com">sales@travelfreetravel.com</a>
<br/>

<br/>

Regards, <br/>
Sahamati Marga, House no. 17,Gairidhara, Kathmandu-02<br> <br/>
<!-- <img src="<?php echo  $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>" style="height:40px;width:200px;" alt="<?php echo domain_name();?>"> -->
            </td>
         </tr>
		  <tr>
         <td><!-- <img src="extras/system/template_list/template_v3/images/"> -->
           <?php
$burl=str_replace('supervision/','', base_url());

?>
           <img src="<?=$burl.'extras/system/template_list/template_v3/images/emailtemplate_banner.jpg' ?>">


        </td>
			 <!-- <td><img  src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>"	 style="height:100px;" alt=" <?php echo domain_name();?>"></td> -->
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


