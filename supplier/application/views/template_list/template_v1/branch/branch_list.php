<?php if ($ID) {
	$tab1 = " active ";
	$tab2 = "";
} else {
	$tab2 = " active ";
	$tab1 = "";
}

// debug($country_list);die;


$i=0;
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
if(!empty($search_params)){
	if (is_array($search_params)) {
	extract($search_params);
	}	
}


?>
<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
<li role="presentation" class="<?php echo $tab2; ?>"><a
		href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
	<i class="fa fa-users"></i> Branch List </a>
	</li>
	
	<li role="presentation" class="<?php echo $tab1; ?>"><a
		id="fromListHead" href="#fromList" aria-controls="home" role="tab"
		data-toggle="tab"> <i class="fa fa-edit"></i> Add Branch Users
	</a></li>
	
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">
<?php //if($domain_admin_exists == false) { ?>
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab1; ?>" id="fromList">
<div class="panel-body"></div>
<div class="tab-content">

			<form action="<?php echo base_url(); ?>index.php/branch_users/add_branch/<?php echo $ID?>"
							method="post" enctype="multipart/form-data"
							class='form form-horizontal validate-form'>

				<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Branch User Name <span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="supplier_name" id="name" value="<?php echo isset($agency_name)?$agency_name:'';?>" 
											data-rule-required='true' placeholder="Enter Users Name"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>
							<?php
								// if($ID){
								// 	$action ='';
								// }else{
								// 	$action ="onblur='checkIsunique({table_name:'user',column_name:'email',field_value:this,label:'Email',hidden_field:'email_hidden_text',hidden_error:'email_hidden_text_error'});'"; 

								// 	$action .="onmouseleave='checkIsunique({table_name:'user',column_name:'email',field_value:this,label:'Email',hidden_field:'email_hidden_text',hidden_error:'email_hidden_text_error'});'";
								// }
							?>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Email<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<input type="email" <?php echo isset($email)?'disabled=disabled':'';?> name="email" id="email" value="<?php echo isset($email)?$email:'';?>"
										data-rule-number="true" data-rule-required='true' placeholder="Email"
										class='form-control add_pckg_elements' required="">

										<input type="hidden" name="email_hidden_text" id="email_hidden_text">
										<span id="email_hidden_text_error" class="error"></span>

								</div>
							</div>	
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Password<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<input type="password" <?php echo isset($email)?'disabled=disabled':'';?> name="password" id="password" value="<?php echo isset($password)?$password:'';?>"

										data-rule-number="true" data-rule-required='true' placeholder="Password"
										class='form-control add_pckg_elements'  required="">
								</div>
							</div>	
							


							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Phone No<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<input type="text" name="phone_no" id="p_price"
										data-rule-number="true"  value="<?php echo isset($phone)?$phone:'';?>" data-rule-required='true' placeholder="Phone No" onblur="check_phones()"
										class='form-control add_pckg_elements numeric' max='11' min='8' required="">
								</div>
							</div>	
							<div class='form-group'>
							
								<label class='control-label col-sm-3' for='validation_country'>Country<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='country' id="country" required>
										<!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
										<option value="">Select Location</option>
				                        <?php foreach ($country_list as $coun) {?>
				                        
				                        <?php 
											$s_selected = '';
					                        if($country_origin==$coun['origin']){
					                        	$s_selected = "selected=selected";
					                        }
										?>

				                        <option value='<?php echo $coun['origin']; ?>' <?=$s_selected?> ><?php echo $coun['country_name']; ?></option>
				                        <?php }?>
				                      </select>
								</div>
							</div>
							<div class='form-group' id="city_name_div">
								<label class='control-label col-sm-3' for='validation_current'>City<span style="color:red">*</span>
								</label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										name='cityname' id="cityname" required>
										<option value=''>Select city</option>
									</select>
								</div>
							</div>
<!-- 							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>City List
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" class="normal" id="textbox" name="cityname" style="width:450px;" required></td>
								</div>
							</div> -->
							
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Location<span style="color:red">*</span>
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="location" id="location" placeholder="Enter Location"
										data-rule-required='true'
										class='form-control add_pckg_elements' required value="<?php echo isset($location)?$location:''?>">
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_company'>Branch
									Id Proof<span style="color:red">*</span></label>
                                <?php

                                if(isset($image)
                                ){
                                    echo '<img height="80px" width="120px" alt="" src="'.get_host().str_replace('../','/',DOMAIN_IMAGE_DIR).$image.'">';
                                }
                                ?>
								<div class='col-sm-4 controls'>
									<?php 
										$image_required = 'required';
										if(isset($image)){
											$image_required = '';
										}
									?>
									<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='true' id='photo'
										name='photo' <?=$image_required?>> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Package Image</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Address<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<textarea rows="4" cols="15" class="form-control" name="address" required=""><?php echo isset($address)?$address:''; ?></textarea>
								</div>
							</div>							
							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										
										<input class="btn btn-primary" type="submit" id=sup_submit value="Submit">&nbsp;&nbsp;
											<a class='btn btn-primary' href="<?php echo base_url(); ?>branch_users/car_branch_list"> Cancel</a>
									</div>
								</div>
							</div>
			</form>
	</div>
</div>
<?php //} ?>
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab2; ?>" id="tableList">
<!--/************************ GENERATE Filter Form ************************/-->
<div class="clearfix"></div>
<!--/************************ GENERATE Filter Form ************************/-->
<div class="panel-body"><?php
/************************ GENERATE CURRENT PAGE TABLE ************************/
echo get_table(@$table_data);
/************************ GENERATE CURRENT PAGE TABLE ************************/
?></div>
</div>
</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->
<?php
function get_table($table_data='')
{
	$table = '';
	$pagination = $GLOBALS['CI']->pagination->create_links();

	$table .= $pagination;
	$table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed" id="branch_users">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>
   <th>Branch Name</th>
   
   <th>Email</th>
   <th>Phone Number</th>
   <th>Country</th>
   <th>City</th>
   <th>Status</th>
   <th>Action</th>
   </tr></thead><tbody>';

	if (valid_array($table_data) == true) {
		
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {
			
			$table .= '<tr>
			<td>'.(++$i).'</td>

			<td class="hand-cursor">'.$v['agency_name'].'</td>
			
			<td>'.$v['email'].'</td>
			<td>'.$v['phone'].'</td>
			<td>'.$v['country_name'].'</td>
			<td>'.$v['city_name'].'</td>
			<td>'.get_status_toggle_button($v['status'], $v['user_id'],$v['uuid']).'</td>
			<td>'.get_edit_button($v['user_id']).'</td></tr>';
		}
	} else {
		// $table .= '<tr><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td></tr>';
	}
	$table .= '</tbody></table></div>';
	return $table;
}

function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="label label-success"><i class="fa fa-circle-o"></i> '.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '<span class="label label-danger"><i class="fa fa-circle-o"></i> '.get_enum_list('status', INACTIVE).'</span>
		<a role="button" href="" class="hide">'.get_app_message('AL0020').'</a>';
	}
}

function get_status_toggle_button($status, $user_id, $uuid='')
{
	$status_options = get_enum_list('status');
	return '<select class="toggle-user-status" data-user-id="'.$user_id.'" data-uuid="'.$uuid.'">'.generate_options($status_options, array($status)).'</select>';
	/*if (intval($status) == INACTIVE) {
		return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
	} else {
		return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
	}*/
}

function get_edit_button($id)
{
	return '<a role="button" href="'.base_url().'index.php/branch_users/add_car_branch/'.$id.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';
	/*<a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
		<span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a>*/
}


?>
<script>
// function checkMailStatus(){
$(document).ready(function() {
	 //$('#branch_users').dataTable();
	$('.toggle-user-status').on('change', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/branch_users/';
		if (parseInt(_user_status) == 1) {
			_opp_url = _opp_url+'activate_account/';
		} else {
			_opp_url = _opp_url+'deactivate_account/';
		}
		_opp_url = _opp_url+$(this).data('user-id')+'/'+$(this).data('uuid');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
		});
	});

});

$(document).ready(function(){
	//$('#branch_users').dataTable();
	var country_id ="<?php echo isset($country_origin)?$country_origin:'';?>";
	var city = "<?php echo isset($city)?$city:''?>";
	if(country_id&&city){
		$.ajax({
	           url: "<?php echo base_url() ?>"+'index.php/supplier/get_active_city/'+country_id+"/"+city,
	           dataType: 'json',
	           success: function(json) {
		           	if(json.result=='<option value="">Select City</option>'){
		           		$('#city_name_div').addClass('hide');
		           	}
		           	else{
		           		$('select[name=\'cityname\']').html(json.result);
		           		$('#city_name_div').removeClass('hide');
		           	}
	           }
   		});
	}

	
     $('#country').on('change', function() {

       $.ajax({

           url: "<?php echo base_url() ?>"+'index.php/supplier/get_active_city/' + $(this).val(),
           dataType: 'json',
           success: function(json) {
           	if(json.result=='<option value="">Select City</option>'){
           		$('#city_name_div').addClass('hide');
           	}
           	else{
           		$('select[name=\'cityname\']').html(json.result);
           		$('#city_name_div').removeClass('hide');
           	}
           }
       	});
     });
    $("#cityname").on('click',function(){
    	var dropdownVal=$(this).val();
        $("#textbox").val(dropdownVal); 
	});

	
});
$(document).ready(function()
{
$("#email").blur(function(){
        // alert('ok');
        var email  = $("#email").val();
		// console.log(email);
		$.ajax({
				type:'post',
				url:"<?=base_url('index.php/branch_users/check_duplicate')?>",// put your real file name 
				data:{email: email},
				success:function(result){
					if(result==1){
						$("#email").val('');
					}
				
					}
		});
    });
});


</script>

<script language="javascript">

function check_phones() {

    var phone_a = document.getElementById('p_price').value;
    
    if (!(phone_a.length>=10 && phone_a.length<=10)) {
    alert('Please provide a valid Phone nummber(min 10 and max 12 digits)');
    // alert(card.length);
    card.focus;
    return false;
 }
 else
 {
 	return true;
 }
}</script>

