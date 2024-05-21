<?php if ($ID) {
	$tab1 = " active ";
	$tab2 = "";
} else {
	$tab2 = " active ";
	$tab1 = "";
}
$ii=0;
// echo "in";die;
$data=$user_data[0];
// debug($data);
// echo get_host().DOMAIN_IMAGE_DIR.$data['driver_photo'];die;
if(!empty($data))
{
$full_name=$data['full_name'];
$phone=$data['phone'];
$email=$data['email'];
$driver_photo=$data['driver_photo'];
$address=$data['address'];
$license_no=$data['license_no'];
$validity=$data['full_name'];
$badge_no=$data['badge_no'];
$cart_number=$data['cart_number'];
$badge_validity=$data['badge_validity'];
$police_number=$data['police_number'];
$police_validity=$data['police_validity'];
$cart_validity=$data['cart_validity'];
}

$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
if(!empty($search_params)){
	if (is_array($search_params)) {
	extract($search_params);
	}	
}
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/jquery.validate.js'), 'defer' => 'defer');
?>
<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->

	<li role="presentation" class="<?php echo $tab1; ?>"><a
		id="fromListHead" href="#fromList" aria-controls="home" role="tab"
		data-toggle="tab"> <i class="fa fa-edit"></i> Add Driver
	</a></li>
	
	<li role="presentation" class="<?php echo $tab2; ?>"><a
		href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
	<i class="fa fa-users"></i>Driver List </a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content">
<?php 
//if($domain_admin_exists == false) { ?>
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab1; ?>" id="fromList">
<div class="panel-body"></div>
<div class="tab-content">

			<form action="<?php echo base_url(); ?>index.php/car_supplier/driver_list/<?=$ID?>"
							method="post" enctype="multipart/form-data"
							class='form form-horizontal validate-form' id="driver_form">
							
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Driver Name<span style="color:red;">*</span> </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="full_name" id="full_name" value="<?php echo isset($full_name)? $full_name:''?>" 
											data-rule-required='true' placeholder="Driver Name"
											class='form-control add_pckg_elements alpha' required>
										<span class="text-danger"><?php echo form_error('full_name')?></span>
									</div>
								</div>
							</div>
								<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Contact Number<span style="color:red;">*</span> </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="phone" id="phone" value="<?php echo isset($phone) ? $phone : '';?>" 
											data-rule-required='true' placeholder="Contact Number"
											class='form-control add_pckg_elements numeric  validate[required]'  required maxlength="12">
									<span class="text-danger"><?php echo form_error('phone')?></span>
									</div>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Email<span style="color:red;">*</span></label>
								<div class='col-sm-4 controls'>
									<input type="email" name="email" id="email" value="<?php echo isset($email) ? $email : '';?>" 
										data-rule-number="true"  placeholder="Email"
										class='form-control add_pckg_elements' required="" onkeypress="return emailkeypress(event);"   onblur="checkIsunique({table_name:'user',column_name:'email',field_value:this,label:'Email',hidden_field:'email_hidden_text',hidden_error:'email_hidden_text_error'});"

										 onmouseleave="checkIsunique({table_name:'user',column_name:'email',field_value:this,label:'Email',hidden_field:'email_hidden_text',hidden_error:'email_hidden_text_error'});" >

										 <input type="hidden" name="email_hidden_text" id="email_hidden_text">
										<span id="email_hidden_text_error" class="error"></span>


										<span class="text-danger"><?php echo form_error('email')?></span>
								</div>
							</div>	
							<?php if($ID=='')
							{
								?>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Password<span style="color:red;">*</span></label>
								<div class='col-sm-4 controls'>
									<input type="password" name="password" id="password" value="<?php echo isset($password) ? $password : '';?>" 
										data-rule-number="true" data-rule-required='true' placeholder="Password"
										class='form-control add_pckg_elements'  required="">
										<span class="text-danger"><?php echo form_error('password')?></span>

								</div>
							</div>

							<?php
}
if($GLOBALS['CI']->entity_user_type == ADMIN){

							?>
							

<div class='form-group'>
								<label class='control-label col-sm-3' for='branch_id'>Branch<span style="color:red;">*</span></label>
								<div class='col-sm-4 controls'>
								
<?php
$admin_id=$this->entity_user_id;
$admin_branch_id=$this->entity_branch_id;
$this->db->select('*');

$this->db->where(array('agency_name !='=> '','user_type'=>'7'));

$this->db->from('user ');

 $this->db->distinct('branch_id');
// $this->db->join('user as U','tbl_usercategory.usercategoryid=tbl_user.usercategoryid');


$query = $this->db->get()->result_array();
$this->db->select('*');
$this->db->from('user');

$this->db->where(array('agency_name !='=> '','user_type'=>'7'));
 $this->db->distinct('branch_id');

$query1 = $this->db->get();
$limit=$query1->num_rows();
// echo $limit;
// print_r($query);
?>
<select id='branch_id' name='branch_id' class='form-control' >
<option value='100' selected>Select branch</option>
<?php
for($i=0;$i<$limit;$i++)
{
?>
<option value='<?php echo $query[$i]['user_id']?>'><?php echo $query[$i]['agency_name']?></option>
<?php
}

?>
</select>

								</div>
								</div>
								<div class="form-group">
      <label class="control-label col-sm-3" for="supplier_id">Supplier:<span style="color:red;">*</span></label>
      <div class="control-label col-sm-4">
        <select class="form-control" id="supplier_id" name="supplier_id" required>
      	
         </select>
     </div>
    </div>
    
<?php

}

?>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_company'>Image<span style="color:red;">*</span></label>
								<div class='col-sm-4 controls'>

									<?php 
										$required ='required';
										if(isset($driver_photo)){
											$required='';
										}

											?>
											
											
                    
					<img height="80px" width="120px" alt="" src="<?php echo get_host().DOMAIN_IMAGE_DIR.$data['driver_photo']?>">
									
								
									<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='true' id='photo'
										name='photo' <?=$required?>> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Image</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Address<span style="color:red;">*</span></label>
								<div class='col-sm-4 controls'>
									<textarea rows="4" cols="15" class="form-control" name="address" required="" id='address' onblur="check_fields(address)"><?php echo isset($address)?$address:'';?></textarea>
									<span class="text-danger"><?php echo form_error('address')?></span>
								</div>
							</div>	
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>License Number<span style="color:red;">*</span>
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="license_no" id="license_no" placeholder="License Number"
										value="<?php echo isset($license_no)?$license_no:''?>" 
										class='form-control add_pckg_elements' 
										onblur="check_fields(license_no)" onkeypress="return alphanumeric(event)" required="required">
										<span class="text-danger"><?php echo form_error('license_no')?></span>

										<span id="license_no_hidden_text_error" class="unique-error"></span>


								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Validity<span style="color:red;">*</span>
								</label>
								<div class='col-sm-4 controls'>
									<input onblur="check_fields(validity)"  type="text" name="validity" id="validity" placeholder="Validity Date"
										value="<?php echo isset($validity) ? date("d-m-Y",strtotime($validity)) : '';?>" 
										class='form-control add_pckg_elements date-class'>
									<span class="text-danger"><?php echo form_error('validity')?></span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Badge Number<span style="color:red;">*</span>
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="badge_no" id="badge_no" onblur="check_fields(badge_no)" placeholder="Badge Number"
										value="<?php echo isset($badge_no) ? $badge_no : '';?>" 
										class='form-control add_pckg_elements'>
										<span class="text-danger"><?php echo form_error('badge_no')?></span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Validity<span style="color:red;">*</span>
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" onblur="check_fields(badge_validity)"  name="badge_validity" id="badge_validity" placeholder="Validity Date"
										value="<?php echo isset($badge_validity) ? date("d-m-Y",strtotime($badge_validity)) : '';?>" 
										class='form-control add_pckg_elements date-class'>

									<span class="text-danger"><?php echo form_error('badge_validity')?></span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Police Verification Number
								<span style="color:red;">*</span></label>
								<div class='col-sm-4 controls'>
									<input type="text" name="police_number" id="police_number" onblur="check_fields(police_number)" placeholder="Police Verification Number"
										value="<?php echo isset($police_number) ? $police_number : '';?>"  
										class='form-control add_pckg_elements'>
									<span class="text-danger"><?php echo form_error('police_number')?></span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Validity<span style="color:red;">*</span>
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="police_validity" id="police_validity" onblur="check_fields(police_validity)" placeholder="Validity Date"
										value="<?php echo isset($police_validity) ? date("d-m-Y",strtotime($police_validity)) : '';?>" 
										class='form-control add_pckg_elements date-class'>
									<span class="text-danger"><?php echo form_error('police_validity')?></span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Display Cart Number<span style="color:red;">*</span>
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="cart_number" id="cart_number" onblur="check_fields(cart_number)" placeholder="Display Cart Number"
										value="<?php echo isset($cart_number) ? $cart_number : '';?>" 
										class='form-control add_pckg_elements'>
									<span class="text-danger"><?php echo form_error('cart_number')?></span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Validity<span style="color:red;">*</span>
								</label>
								<div class='col-sm-4 controls'>
									<input onblur="check_fields(cart_validity)" type="text" name="cart_validity" id="cart_validity" placeholder="Validity Date" value="<?php echo isset($cart_validity) ? date("d-m-Y",strtotime($cart_validity)) : '';?>" 
										
										class='form-control add_pckg_elements date-class'>

										<span class="text-danger"><?php echo form_error('cart_validity')?></span>
								</div>
							</div>
							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										
										<input class="btn btn-primary" type="submit" id=sup_submit value="Submit">&nbsp;&nbsp;
											<a class='btn btn-primary' href="<?php echo base_url(); ?>car_supplier/driver_list"> Cancel</a>
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
	//$pagination = $GLOBALS['CI']->pagination->create_links();
	//$table .= $pagination;
	$table  .= $GLOBALS['CI']->pagination->create_links();
	$table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed" id="driver_table">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>';
    if($GLOBALS['CI']->entity_user_type == ADMIN){
        $table .= '<th>Branch Name</th>';
        $table .= '<th>Supplier Name</th>';
    }
   $table .='
   <th>Driver Name</th>   
   <th>Email</th>
   <th>Phone Number</th>    
   <th>Status</th>
   <th>Action</th>
   </tr></thead><tbody>';

	if (valid_array($table_data) == true) {
		 
		
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {	
		//echo $v['supplier_name']	

			// echo $k;
		// echo $v['supplier_name'];
		// echo "<br>";
			$action = '';
			$action .=get_edit_button($v['driver_id'],$current_record);
			$action .=get_delete_button($v['driver_id']);
			$table .= '<tr>
					<td>'.(++$ii).'</td>';
            if($GLOBALS['CI']->entity_user_type == ADMIN){
                $table .= '<td>'.ucfirst($v['branch_name']).'</td>';
                $table .= '<td>'.ucfirst($v['supplier_name']).'</td>';
            }

			$table .= '
			<td class="hand-cursor">'.$v['full_name'].'</td>			
			<td>'.$v['email'].'</td>
			<td>'.$v['phone'].'</td>			
			
			<td>'.get_status_toggle_button($v['status'], $v['driver_id']).'</td>
			<td>'.$action.'</td>
			
			</tr>';
		}
	} else {
		// $table .= '<tr><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td><td>'.get_app_message('AL005').'</td></tr>';
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

function get_status_toggle_button($status, $driver_id)
{
	$status_options = get_enum_list('status');
	return '<select class="toggle-user-status" data-table="driver_list" data-driver-id="'.$driver_id.'">'.generate_options($status_options, array($status)).'</select>';

}

function get_edit_button($id,$cr)
{
	return '<a role="button" href="'.base_url().'index.php/car_supplier/driver_list/'.$id.'/'.$cr.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';
	
}
function get_delete_button($origin){
	return '<a role="button" href="#" class="btn btn-sm btn-danger del-car-asset-btn" data-id="'.$origin.'" data-table="driver_list"><i class="fa fa-trash"></i>
		'." ".get_app_message('AL00342').'</a>';
}

?>

<script>

$(document).ready(function(){

    $('#branch_id').on('change',function()
    {
        var branch_id = $(this).val();
       //alert(branch_id);

            $.ajax({
                type:'POST',
                url:'<?php echo base_url() ?>index.php/car/getsupplier',
                data:'branch_id='+branch_id,
                success:function(data)
                {
                	// alert(data);
                    $('#supplier_id').html('<option value="">Select Supplier</option>'); 
                    var dataObj = jQuery.parseJSON(data);
                        $(dataObj).each(function()
                        {
                            var option = $('<option />');
                            option.attr('value', this.user_id).text(this.agency_name);           
                            $('#supplier_id').append(option);
                        });
                }
            }); 
     
    });
});


// $('.date1').datepicker({dateFormat: 'dd-mm-yy'});
$(document).ready(function() {

	var api_url ="<?php echo base_url()?>";
	 var license_state = false;
	 var edit_profile = "<?php echo $ID?>"; 
	 if(edit_profile){
	 	license_state = true;
	 }
	$("#license_no").blur(function(){

		if(!edit_profile){
	var license_no = $(this).val();	
		if (license_no == '') {
			  	license_state = false;
			  	return;
			}
			$.ajax({
				url:api_url+'user/check_unique_field',
				type:'post',
				data:{field_name:"license_no",field_value:license_no,table:"driver_list"},
				success:function(res){
					if(res==1){
						license_state = false;
						$("#license_no_hidden_text_error").html("This License Number Already Exists in System, Try giving another License number");
					}else{
						license_state = true;
						$("#license_no_hidden_text_error").html('');
					}
				},
				error:function(res){
					console.log("AJAX ERROR LICENSE NUMBER");
				}

			})
	 	}

		

	});

//$("#driver_table").dataTable();

	$("#sup_submit").click(function(){

		console.log(license_state);

		if(license_state){
			$("#license_no_hidden_text_error").html("");
			return true;
		}
		return false;
	});
	 // $("#driver_form").validate({
		// 		rules: {
		// 			phone: {
		// 				required: true,
		// 				min:10,
		// 				max:12
		// 			},
		// 		},
		// 		messages: {
		// 			phone: "Please Enter 10 digit number",
		// 		}
		// 	});



	// $("#driver_list").dataTable();
	$(".date-class").datepicker({		
	    changeMonth: true,
	    numberOfMonths: 1,
	    minDate: new Date()
	});
	var car_base_url = "<?php echo  base_url()?>";
	$(document).on('change', '.toggle-user-status', function(e) {
		console.log("entered");
		e.preventDefault();
		var _user_status = this.value;
		// alert(_user_status); return false;
		var _opp_url = app_base_url+'index.php/car_supplier/';
		if (parseInt(_user_status) == 1) {
			_opp_url = _opp_url+'active_car_driver/';
		} else {
			_opp_url = _opp_url+'deactive_car_driver/';
		}
		_opp_url = _opp_url+$(this).data('table')+'/'+$(this).data('driver-id');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function(res) {
			if(res==1){
				toastr.info('Updated Successfully!!!');	
			}else{
				toastr.warning('Not Updated !!!!');
			}
			
		});
	});
	$(".del-car-asset-btn").on("click",function(){
			


/*111*/

var s;
if (confirm("Do you want to remove?"))
				 {
   s=1;
}
 else {
   s=0;
}
			var id = $(this).data('id');
			var table = $(this).data('table');
			toastr.info('Please Wait!!!');
			if(s==1)
			{
			$.ajax({
				
				url:car_base_url+"index.php/car_supplier/delete_record/"+table+"/"+id+"/driver_fk_id",
		
				
				success:function(res){
					if(res==1){
					
						toastr.info('Updated Successfully!!!');
						location.reload();
					}
					else{
						toastr.info('Not Deleted!!!');
					}
				},

					error:function(res){
					alert("Technical Issue");
				}
		})
		}
		
else{
						toastr.info('Not Deleted!!!');
					}
		
/*222*/

/*

			var id = $(this).data('id');
			var table = $(this).data('table');
			toastr.info('Please Wait!!!');
			$.ajax({
				url:car_base_url+"index.php/car_supplier/delete_record/"+table+"/"+id+"/driver_fk_id",
				success:function(res){
					if(res==1){
						toastr.info('Updated Successfully!!!');
						location.reload();
					}else{
						toastr.info('Not Deleted!!!');
					}
				},
				error:function(res){
					alert("Technical Issue");
				}
			})*/
		});

});
$(document).on('change', '#country_id', function() {

		var country = $(this).val();
		var iso_country_code = $(this).find("option:selected").data('iso-code');
		$("#iso_country_code").val(iso_country_code);
		initAutocomplete();
		
});

var iso_country_code = $("#country_id").find("option:selected").data('iso-code');
$("#iso_country_code").val(iso_country_code);
</script>
<script type="text/javascript">

	var placeSearch, autocomplete;      
      function initAutocomplete() {
        //initMap();
         var iso_country = $("#iso_country_code").val();
         if(iso_country !=''){
             iso_country = iso_country;
         }else{
             iso_country = "in";
         }
         //console.log("iso_country"+iso_country);
         var autocomplete_val = document.getElementById('full_city_name');
         var options = {
             types: ['(cities)'],             
             componentRestrictions: {country: iso_country}
            };
         autocomplete = new google.maps.places.Autocomplete(autocomplete_val,options);

        autocomplete.addListener('place_changed', fillInAddress);
     
      }

      function fillInAddress() {
        var place = autocomplete.getPlace();
        var full_address=place.formatted_address;
        var city_name = place.vicinity;
        $("#full_city").val(full_address);
        $("#city_name").val(city_name);   
        $("#current_location").val(full_address);   
     }
</script>



<script language="javascript">

function check_fields(id) {

    var phon = document.getElementById(id).value;
    
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




<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgTSEDj6u-O1SjKXNVFmlwnev1Hr4g0zs&libraries=places&callback=initAutocomplete"
        async defer></script>   -->