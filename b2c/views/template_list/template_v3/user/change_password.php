<div id="general_change_password" class="bodyContent col-md-12">
<div><?php //echo $this->session->flashdata('message'); ?></div>
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->
			Change Password
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
			<?php
			/** Generating Change Password Form**/	
			echo $this->current_page->generate_form('change_password');
			?>
		</div><!-- PANEL BODY END -->
	</div><!-- PANEL WRAP END -->
</div>
<script>
$( "#change_password_submit" ).click(function() {
	var oldP=document.getElementById("current_password").value;
    var newP=document.getElementById("new_password").value;
    var confirmP =document.getElementById("confirm_password").value;
        var regex_lowercase = '/[a-z]/';
        var regex_uppercase = '/[A-Z]/';
        var regex_number = '/[0-9]/';
        var regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';
    if(oldP!=""&&newP!=""&&confirmP!="")
    {
        var regularExpression = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/;
        if(!regularExpression.test(newP)) {
            alert("password should contain atleast one number and one special character");
            return false;
        }
        if(newP==confirmP)
         {
          return true;
         }
         else
          {
            alert("Confirm password is not same as you new password.");
            return false;
          }
    
    }
    else
    {
     alert("All Fields Are Required");
     return false;
    }
});
</script>
