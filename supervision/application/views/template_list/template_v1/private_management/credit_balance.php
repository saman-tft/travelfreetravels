<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel panel-default clearfix"><!-- PANEL WRAP START -->
	<?php if(isset($agent_details) == true && valid_array($agent_details) == true) {?>
		<div class="panel-heading"><!-- PANEL HEAD START -->
		<div class="panel-title">
		<h4><u>Agency Name:</u>   <?=$agent_details['agency_name']?>
		<br /><u>Balance:</u>   <?=$agent_details['balance']?>
		<br /><u>CreditLimit:</u>   <?=$agent_details['credit_limit']?>
		<br /><u>Due Amount:</u>   <?=$agent_details['due_amount']?>
		<br /><u>Currency:</u>   <strong id="agent_base_currency"><?=$agent_details['agent_base_currency']?></strong></h4>
		</div>
		</div>
	<?php } ?>
		<div class="clearfix"></div>
       <?php 
       /************************ GENERATE CURRENT PAGE FORM ************************/
       $agent_id=$_GET["agent_id"];
		if(isset($agent_id) == true && empty($agent_id) == false){
			$form_data['agent_id'] = $agent_id;
			echo $this->current_page->generate_form('credit_balance', $form_data);
		} else {
			// debug($form_data);exit;
			echo $this->current_page->generate_form('credit_balance_agent_list', $form_data);
		}
       /************************ GENERATE UPDATE PAGE FORM ************************/
       ?>
	</div><!-- PANEL WRAP END -->
</div>

<!-- HTML END -->
<script>
$(document).ready(function() {
    $("#agent_id").val("<?php echo $_GET["agent_id"]; ?>");
	var agent_base_currency = $('#agent_base_currency').text().trim();
	if(agent_base_currency!=''){
		$('#amount').after('<strong class="text-danger">NOTE: currency is '+agent_base_currency+'</strong>');
	}
	$('#agent_id').change(function(){
		var agent_id = $(this).val().trim();
	   window.location.href = app_base_url+'index.php/private_management/credit_balance?agent_id='+agent_id;	
	});
});
</script>