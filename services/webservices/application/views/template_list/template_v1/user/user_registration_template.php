<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Notification</title>
</head>
<body>
<div>
<p>Dear <?php echo $first_name.' '.$last_name; ?>,</p>
<hr>
<p>Your Details has been Registered Successfully !!!</p>
<p>Please <strong><a href="<?php echo $activation_link;?>">Click Here</a></strong> to activate your account </p>
</div>
<hr>
<p>Thanks & Regards</p>
<p><a href="<?php echo base_url()?>"><?php echo domain_name()?></a></p>
</body>
</html>
