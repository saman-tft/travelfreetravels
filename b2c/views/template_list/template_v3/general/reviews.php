	<style type="text/css">
		.flyinputsnor{
			    border: 1px solid #c1c1c1;
    box-shadow: 0 0 10px -5px #ccc inset;
    display: block;
    color: #717171;
    height: 34px;
    border-radius: 3px;
    overflow: hidden;
    padding: 0 10px;
    width: 100%;
    font-size: 14px;
    margin: 0 0 15px;

		}
	</style>
	<style type="text/css">
  .fromtopmargin .b2b_agent_profile.agent_regpage.agentmyn .container{
    background: none;
  }

</style>

 <?=@$message;?>
	<div class="container">
		<p id="user_message_review"></p>
		<div class="lblbluebold16px"><h1>Post a Review</h1></div>
		<div class="mlgnformin_new">
			<form class="form-horizontal"  method="post" name="post_review" role="form" action="<?php echo base_url();?>general/set_post_general_review">
				<div class="form-group">
					<label class="control-label col-md-5 col-xs-4" for="user_name">First Name: <strong class="text-danger">*</strong></label>
					<div class="col-md-7 col-xs-8">
						<input aria-required="true" class="form-control mntxt alpha_only" id="user_name" name="user_name" placeholder="" required="required" type="text" value="<?php echo $firstName; ?>">
					</div>

				</div>
				<div class="form-group">
					<label class="control-label col-md-5 col-xs-4" for="user_name">Last Name: <strong class="text-danger">*</strong></label>
					<div class="col-md-7 col-xs-8">
						<input aria-required="true" class="form-control mntxt alpha_only" id="user_lname" name="user_lname" placeholder="" required="required" type="text" value="<?php echo $lastName; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5 col-xs-4" for="user_email">Email ID: <strong class="text-danger">*</strong></label>
					<div class="col-md-7 col-xs-8">
						<input aria-required="true" class="form-control mntxt" id="user_email" name="user_email" placeholder="" required="required" type="email" value="<?php echo $userEmail; ?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-5 col-xs-4" for="user_mobile">Phone Number:<strong class="text-danger">*</strong></label>
					<div class="col-md-7 col-xs-8">
						<div class="col-xs-5 nopad airform">
							<!-- <span class="formlabel">Country Code <sup class="text-danger no_block">*</sup></span> -->
							<div class="selectedwrap">
								<?php $usPcode[] = $userPcode; ?>
					                <select name="pn_country_code" id="country_code" required="" aria-required="true" class="mySelectBoxClass flyinputsnor">
					                  
					                              <option value="+977">Nepal +977</option>
					                              <?php foreach($country_code as $c__k => $__country) {
					                                ?><option value="<?=trim(@$c__k)?>" <?php if($c__k == '+977') {echo 'selected';} ?>><?=@$__country?></option><?php 
					                              }?>
					                </select>
							</div><!--<input type="text" placeholder="+41" class="pre_put form-control" required="" name="pn_country_code" aria-required="true">-->
						</div>
						<div class="col-md-7 col-xs-7 airformleft">
							<input type="text" name="user_mobile" id="phone_number" class="form-control mobile" maxlength="150" required="required" aria-required="required" value="<?php echo $userPhone; ?>">
						</div>
					</div>
				</div>
<div class="form-group">
						<label class="control-label col-md-5 col-xs-4" for="">Module Type</label>
<div class="col-md-7 col-xs-8 ">
				<select name="module" required="" aria-required="true" class="mySelectBoxClass flyinputsnor">
				   <option value="Flights">Flights</option>
				   <option value="Hotels">Hotels</option>
			       <option value="Activities">Activities</option>
			       <option value="Transfers">Transfers</option>
			       <option value="Holidays">Holidays</option>
			       <option value="Others">Others</option>
			      
				   
				</select>
</div>
</div>
				<div class="form-group">
					<label class="control-label col-md-5 col-xs-4" for="">Have you booked earlier with us?</label>
					<div class="col-md-7 col-xs-8 yes_no">
						<input class="" id="previously_booked1" name="previously_booked" type="radio" value="1"><label for="previously_booked1">Yes</label> &nbsp; &nbsp; <input checked="checked" class="" id="previously_booked0" name="previously_booked" type="radio" value="0"><label for="previously_booked0">No</label>
					</div>
				</div>
				<?php  if(($userName=="")&&($userEmail=="")){ ?>
					<div class="form-group">
					<label class="control-label col-md-5 col-xs-4 reg _user" for="">Register User?</label>
					<div class="col-md-7 col-xs-8 yes_no">
						<?=$exist_user_field['open_tag']?>
							<input type="radio" class="" value="1" name="exist_user" id="exist_user1" <?=$exist_user1?>>
							<label for="exist_user1">Yes </label> &nbsp; &nbsp;  
							<input type="radio" class="" value="0" name="exist_user" id="exist_user0" <?=$exist_user0?> checked="true">
							<label for="exist_user0">No </label>
						<?=$exist_user_field['close_tag']?>
					</div>
				</div>
				<?php }else{ ?>
				<div class="form-group hide">
					<label class="control-label col-md-5 col-xs-4 reg _user" for="">Register User?</label>
					<div class="col-md-7 col-xs-8 yes_no">
						<?=$exist_user_field['open_tag']?>
							<input type="radio" class="" value="1" name="exist_user" id="exist_user1" <?=$exist_user1?> checked="true" disabled>
							<label for="exist_user1">Yes </label> &nbsp; &nbsp;  
							<input type="radio" class="" value="0" name="exist_user" id="exist_user0" <?=$exist_user0?> disabled>
							<label for="exist_user0">No </label>
						<?=$exist_user_field['close_tag']?>
					</div>
				</div>
			<?php }  ?>
				
				<div class="form-group">
					<label class="control-label col-md-5 col-xs-4" for="comment">Review: <strong class="text-danger">*</strong></label>
					<div class="col-md-7 col-xs-8">
						<textarea aria-required="true" class="form-control mntxt" id="comment" name="comment" placeholder="" required="required" rows="3"></textarea>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-md-offset-5 col-sm-10 col-md-9">
						<button class="btn btn-default inblk lgnbtn" type="submit">Submit</button>
					</div>
				</div>

				<input type="hidden" name="created_by" value="<?=@$created_by?>">			
				<input type="hidden" name="module_id" value="<?=@$module_id?>">
				<input type="hidden" name="title" value="<?=@$title?>">
				<input type="hidden" name="address" value="<?=@$address?>">
		
		


			</form>
		</div>
		<?php
		// debug($count_status);

		?>
<script>
$(document).ready(function() {	
	
		$("#exist_user1").click(function(){		
			$('#mylogin').modal('show');
		});
		$('#mylogin').on('hidden.bs.modal', function () {
		    $('#exist_user1').prop('checked',false);
		    $('#exist_user0').prop('checked',true);
		});
		if('<?php echo $userPcode; ?>'){
		$("#country_code").val('<?php echo @$userPcode."_".@$user_phone_country." ".$userPcode;?>');
		}
		 <?php if(($this->session->flashdata('message') !== "") && ($count_status == 1)){ ?>
    $("#success-box-modal1").modal("toggle");
<?php } ?>
	})

</script>