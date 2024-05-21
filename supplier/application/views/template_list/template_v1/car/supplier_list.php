
<?php
// echo "in";
// debug($image);die;
 if ($ID) {
	$tab1 = " active ";
	$tab2 = "";
} else {
	$tab2 = " active ";
	$tab1 = "";
}
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

<!-- HTML BEGIN -->
<head>



  <script src="http://demo.itsolutionstuff.com/plugin/croppie.js"></script>
  
  <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/croppie.css">
</head>
<div id="general_user" class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="<?php echo $tab1; ?>"><a
		id="fromListHead" href="#fromList" aria-controls="home" role="tab"
		data-toggle="tab"> <i class="fa fa-edit"></i> Add Supplier
	</a></li>
	<li role="presentation" class="<?php echo $tab2; ?>"><a
		href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
	<i class="fa fa-users"></i> Supplier List </a>
	</li>

	
	
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

			<form autocomplete="false" action="<?php echo base_url(); ?>index.php/supplier/add_supplier/<?php echo $ID?>"
							method="post" enctype="multipart/form-data"
							class='form form-horizontal validate-form'>

				<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Supplier Name <span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="supplier_name" id="name" value="<?php echo isset($agency_name)?$agency_name:''?>" 
											data-rule-required='true' placeholder="Enter Supplier Name"
											class='form-control add_pckg_elements alpha' required>
									</div>
								</div>
							</div>
							<?php
							if($GLOBALS['CI']->entity_user_type == ADMIN){

							?>
							

<div class='form-group'>
								<label class='control-label col-sm-3' for='branch_id'>Branch<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
								
<?php
$admin_id=$this->entity_user_id;
$admin_branch_id=$this->entity_branch_id;
$this->db->select('*');

$this->db->where(array('agency_name !='=> '','user_type'=>'7'));

$this->db->from('user ');

 $this->db->distinct('branch_id');


$query = $this->db->get()->result_array();
$this->db->select('*');
$this->db->from('user');

$this->db->where(array('agency_name !='=> '','user_type'=>'7'));
 $this->db->distinct('branch_id');

$query1 = $this->db->get();
$limit=$query1->num_rows();
// echo $limit;
// print_r($query);
/*echo $branch_id;
echo "<br/>".$limit;
debug($query);die;*/
?>
<select id='branch_id' name='branch_id' required class='form-control' >
<option value='100'>Select branch <span style="color:red">*</span></option>
<?php
for($i=0;$i<$limit;$i++)
{
	if($i==0)
	{
?>
<option value='<?php echo $query[$i]['user_id']?>' <?php if($query[$i]['user_id'] == $branch_id){ echo "selected"; } ?>><?php echo $query[$i]['agency_name']?></option>
<?php
}
else
{
	?>
	<option value='<?php echo $query[$i]['user_id']?>' <?php if($query[$i]['user_id'] == $branch_id){ echo "selected"; } ?>><?php echo $query[$i]['agency_name']?></option>

	<?php

}
}
}
?>
</select>

								</div>
								</div>

							<?php

								if($ID){
									$action ='';
								}else{
									$action ="onblur='checkIsunique({table_name:'user',column_name:'email',field_value:this,label:'Email',hidden_field:'email_hidden_text',hidden_error:'email_hidden_text_error'});'"; 

									$action .="onmouseleave='checkIsunique({table_name:'user',column_name:'email',field_value:this,label:'Email',hidden_field:'email_hidden_text',hidden_error:'email_hidden_text_error'});'";
								}
							?>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Email<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>

									<input autocomplete="new-email" type="email" <?php echo isset($email)?'disabled=disabled':'';?> name="email" id="email_input"  value="<?php echo isset($email)?$email:''?>"
										data-rule-number="true" data-rule-required='true' placeholder="Email"
										class='form-control add_pckg_elements' required=""    >

										<input type="hidden" name="email_hidden_text" id="email_hidden_text">
										<span id="email_hidden_text_error" class="error"></span>
										<div id="email_alert" class="alert alert-danger" style="display: none;">
  									<strong>Alert!</strong> Email already exists.
								</div>
								</div>
								
							</div>	
							<?php if($ID=='')
							{
								?>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Password<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>

									<input autocomplete="new-password" type="password" <?php echo isset($password)?'disabled=disabled':'';?> name="password" id="password"  value="<?php echo isset($password)?$password:''?>"
										data-rule-number="true" data-rule-required='true' placeholder="Password"
										class='form-control add_pckg_elements' required="">
								</div>
							</div>	
							<?php
						}
						?>
							


							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Phone No<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<input type="text" name="phone_no" id="phone_no"
										data-rule-number="true" data-rule-required='true' placeholder="Phone No" value="<?php echo isset($phone)?$phone:''?>" 
										class='form-control add_pckg_elements numeric' onblur="check_phone()" required="">
								</div>
							</div>	
						
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_country'>Country<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='country' id="country" required>
										
										<option value="">Select Location</option>

				                        <?php foreach ($country as $coun) {?>
				                        
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
								<label class='control-label col-sm-3' for='validation_company'>Supplier 
									Id Proof <span style="color:red">*</span></label>

									<?php 
										$m_required = 'required';
										if(isset($image)){
											?>
											<a class='pull-left' style='margin-left:18px;' data-id="<?php echo $image; ?>" data-toggle="modal" class="openimg" href="#openModal">
											<?php
                    echo '
					<img height="80px" width="120px" alt="" src="'.get_host().str_replace('../','/',DOMAIN_IMAGE_DIR).$image.'">';
											$m_required = '';
										}
										?>
										</a>
								
								<div class='col-sm-4 controls'>
									<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='true' id='photo'
										name='photo' <?=$m_required?>> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Package Image</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Address<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<textarea rows="4" cols="15" name="address" class="form-control" required=""><?php echo isset($address)?$address:'' ?></textarea>
								</div>
							</div>							
							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										
										<input class="btn btn-primary" type="submit" id=sup_submit value="Submit">&nbsp;&nbsp;
											<a class='btn btn-primary' href="<?php echo base_url(); ?>index.php/supplier/all_car_supplier_list"> Cancel</a>
									</div>
								</div>
							</div>
			</form>
	</div>
</div>

<div class="modal fade " id="openModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header" style='background-color: #f58830;color:white;'>
        <h3 class="modal-title" id="exampleModalLabel">Cropping image</h3>
      </div>
      <div class="modal-body">
      <h4><span style='margin-top:10px;margin-bottom:100px;display:none' id='crp'>Cropped image</span></h4>
        <img id="myImage" class="img-responsive" src="" width='300px' height='300px' alt="">
        <div id="croppieCrop"></div>
      </div>
      <div class="modal-footer">
      
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id='upload'>Save image</button>
        <br>
        <span class='btn btn-success' style='margin-top:10px;display:none' id='ress'></span>
      </div>
    </div>
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
	//$pagination = $GLOBALS['CI']->pagination->create_links();
	// debug($pagination);die;
	//$table .= $pagination;
	$table  .= $GLOBALS['CI']->pagination->create_links();
	$table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed" id="supplier_table">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>';
	if($GLOBALS['CI']->entity_user_type == ADMIN){
	    $table .= '<th>Branch Name</th>';
    }
	$table .='
   <th>Supplier Name</th>
   
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
		//debug($table_data);die;
		foreach ($table_data as $k => $v) {
			
			$table .= '<tr>
			<td>'.(++$i).'</td>';
            if($GLOBALS['CI']->entity_user_type == ADMIN){
                $table .= '<td>'.$v['branchuser_name'].'</td>';
            }
            $table .= '<td class="hand-cursor">'.$v['agency_name'].'</td>
			
			<td>'.$v['email'].'</td>
			<td>'.$v['phone'].'</td>
			<td>'.$v['country_name'].'</td>
			<td>'.$v['city_name'].'</td>
			<td>'.get_status_toggle_button($v['status'], $v['user_id']).'</td>
			<td>'.get_edit_button($v['user_id']).'</td></tr>';
		}
	} else {
		$table .= '<tr><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td></tr>';
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

function get_status_toggle_button($status, $user_id)
{
	$status_options = get_enum_list('status');
	return '<select class="toggle-user-status" data-user-id="'.$user_id.'" >'.generate_options($status_options, array($status)).'</select>';
	/*if (intval($status) == INACTIVE) {
		return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
	} else {
		return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
	}*/
}

function get_edit_button($id)
{
	return '<a role="button" href="'.base_url().'index.php/supplier/car_supplier_list/'.$id.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';
	/*<a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
		<span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a>*/
}

?>

<script>

var el = document.getElementById('croppieCrop');
var uploadCrop = new Croppie(el, {
    viewport: { 
    	width: 200, 
    	height: 200, 
    	// points:[50,120,40,130]
    	type:'square'
	},
    boundary: { 
    	width: 300, 
    	height: 300 
    },
    showZoomer: false,
   // enableOrientation: true
});

var modalThisOpenImage;
var res_img;
var myImageSrcs;
$(document).on("click", ".openimg", function () {
	modalThisOpenImage = $(this);
});
$('#openModal').on('shown.bs.modal', function (e) {
	console.log(modalThisOpenImage);
    myImageSrcs = modalThisOpenImage.data('id');
    var base_imgurl='<?php echo IMG_BASEURL;?>extras/custom/keWD7SNXhVwQmNRymfGN/images/';
	var myImageSrc = base_imgurl+myImageSrcs;

    setTimeout(function(){
		uploadCrop.bind({
		    url: myImageSrc,
		    //orientation: 4
		});
	}, 500);
	
});    

$(document).on('click', '#croppieCrop', function (ev) {
	uploadCrop.result({
		type: 'canvas',
		size: 'viewport',
		showZoomer: false,
	    //enableOrientation: true,
	    enableResize: true
	}).then(function (resp) {
		console.log(resp);
		res_img = resp;
		$('#myImage').show();
		$('#crp').show();
		$('#myImage').attr("src", resp);
	});
});


$('#upload').on('click', function (ev) {
	uploadCrop.result({
		type: 'canvas',
		size: 'original',
		showZoomer: false,
	    //enableOrientation: true,
	    enableResize: true
	}).then(function (resp) {
		$.ajax({
			url: "<?php echo base_url();?>general/upld_croppimg",
			type: "POST",
			data: {"image":resp,"file_name":myImageSrcs},
			success: function (data) {
				html = '<img src="' + resp + '" />';
				$("#myImage").html(html);
				$('#ress').show();
				$('#ress').text('Saved Successfully');
			}
		});
	});
	   // alert(res_img);
		

});




</script>

<script>
$(document).ready(function() {
	
	var app_base_url = "<?php echo base_url()?>";
	$('.toggle-user-status').on('change', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/supplier/';
		if (parseInt(_user_status) == 1) {
			_opp_url = _opp_url+'activate_supplier/';
		} else {
			_opp_url = _opp_url+'deactivate_supplier/';
		}
		_opp_url = _opp_url+$(this).data('user-id');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function(res) {
			if(res==1){
				toastr.info('Updated Successfully!!!');	
			}else{
				toastr.warning('Updated Successfully!!!');
			}
			
		});
	});

	$('#email_input').keyup(function(){
	

		var email = $(this).val();
		$.ajax({
	           url: "<?php echo base_url()?>"+'index.php/supplier/isemailexist/'+email,
	           dataType: 'json',
	           success: function(result) {
		           	if(result.status){
		           		$('#email_alert').show();
		           		$('#sup_submit').attr('disabled','disabled');
		           	}
		           	else{
		           		$('#email_alert').hide();
		           		$('#sup_submit').removeAttr('disabled');
		           	}
	           }
		    });
	});

});
$(document).ready(function(){
//	$("#supplier_table").dataTable();
		var country_id = "<?php echo isset($country_origin)?$country_origin:''?>";
		var city_id = "<?php echo isset($city)?$city:''?>";
		if(country_id&&city_id){
			$.ajax({
	           url: "<?php echo base_url()?>"+'index.php/supplier/get_active_city/'+country_id+"/"+city_id,
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
	           url: '<?php echo base_url()?>index.php/supplier/get_active_city/'+ $(this).val(),
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

</script>

<script language="javascript">

function check_phone() {

    var phon = document.getElementById('phone_no').value;
    
    if (!(phon.length>=10 && phon.length<=12)) {
    alert('Please provide a valid phone number(min 10 and max 12 digits)');
    // alert(card.length);
    card.focus;
    return false;
 }
 else
 {
 	return true;
 }
}</script>

