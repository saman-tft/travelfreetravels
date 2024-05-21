<?php if ($ID) {
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
<div id="general_user" class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
<li role="presentation" class="<?php echo $tab2; ?>"><a
		href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
	<i class="fa fa-users"></i>Active Country City List </a>
	</li>
	<li role="presentation" class="<?php echo $tab1; ?>"><a
		id="fromListHead" href="#fromList" aria-controls="home" role="tab"
		data-toggle="tab"> <i class="fa fa-edit"></i>Add Country & City
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

			<form id="city_form" action="<?php echo base_url(); ?>index.php/branch_users/car_country_list/<?=$ID?>"
							method="post" enctype="multipart/form-data"
							class='form form-horizontal validate-form'>

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_country'>Country</label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='country_id' id="country_id" required>
										
										<option value="">Select Location</option>
				                        <?php foreach ($country_list as $coun) {?>
				                        <?php 
				                        	$o_selected = "";
				                        	if($country_id==$coun['origin']){
				                        		$o_selected = "selected=selected";
				                        	}
				                        ?>
				                        <option value='<?php echo $coun['origin']; ?>' data-iso-code="<?=$coun['iso_country_code']?>" <?=$o_selected?>><?php echo $coun['country_name'] ?></option>
				                        <?php }?>
				                      </select>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>City
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="city" id="city" placeholder="Enter City Name" value="<?php echo (isset($city_name)?$city_name:'')?>" 
										data-rule-required='true'
										class='form-control add_pckg_elements' required>
								</div>
							</div>
							<input type="hidden" name="full_city" id="full_city" value="<?php echo isset($location)?$location:''?>">	
							<input type="hidden" name="iso_country_code" id="iso_country_code" value="<?php echo isset($country_code)?$country_code:''?>">				
							<input type="hidden" name="lat" id="lat" value="<?=isset($lat)?$lat:''?>">
							<input type="hidden" name="lng" id="lng" value="<?=isset($lng)?$lat:''?>">

							<input type="hidden" name="bounds" id="bound" value="<?=isset($bounds)?$bounds:''?>">

							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										
										<input class="btn btn-primary" type="button" id=sup_submit onclick="sup_submit_btn()" value="Submit">&nbsp;&nbsp;
											<a class='btn btn-primary' href="<?php echo base_url(); ?>branch_users/car_country_list"> Cancel</a>
									</div>
								</div>
							</div>
			</form>
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
	$pagination = $GLOBALS['CI']->pagination->create_links();
	// debug($pagination);die;
	$table .= $pagination;
	$table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed" id="car_table">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>
   <th>Country Name</th>   
   <th>City Name</th>
   <th>Action</th>
   </tr></thead><tbody>';

	if (valid_array($table_data) == true) {
		
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {
			
			$action = get_edit_button($v['origin']);
			$table .= '<tr>
			<td>'.(++$i).'</td>

			<td class="hand-cursor">'.$v['country_name'].'</td>			
			<td>'.$v['city_name'].'</td>
			<td>'.$action.'</td>';

			$table .='</tr>';
			

		}
	} else {
		$table .= '<tr><td>'.get_app_message('AL005').'</td><td>No Country</td><td>No City</td></tr>';
	}
	$table .= '</tbody></table></div>';
	return $table;
}

function get_edit_button($id)
{
  return '<a role="button" href="'.base_url().'index.php/branch_users/car_country_list/'.$id.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';	
}
?>

<script>
var is_edit_profile = "<?php echo $ID?>";
// alert(is_edit_profile);
$(document).ready(function() {
	$('#car_table').dataTable();

	

	$('.toggle-user-status').on('change', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/user/';
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

function sup_submit_btn(){
	var is_edit_profile="<?php echo $ID?>";
// alert('test');
		var country_id = $("#country_id").val();
		var city_name = $("#city").val();
		city_name = encodeURIComponent(city_name);
// alert(country_id);
// alert(city_name);
		var loca=$("#full_city").val();
		//alert(loca);
		if(country_id!=''&&city_name!=''){
			$.ajax({
			url:"<?php echo base_url()?>"+"index.php/branch_users/unique_city_name/"+country_id+"/"+city_name+"/"+is_edit_profile,
			success:function(res){
				if(res==1){
					alert("This City Name Already Exists in this system");
				}else{
					// alert("else");
					$("#city_form").submit();
				}
			},
			error:function(res){
				alert("Technical Issues");
			}

			});
		}else{
			alert("Enter the city name details");
		}
		//return false;
	}
</script>

</script>
<script type="text/javascript">
$(document).on('change', '#country_id', function() {

		var country = $(this).val();
		var iso_country_code = $(this).find("option:selected").data('iso-code');
		//var country_name = $(this).find("option:selected").text();
		//$("#country_name").val(country_name);
		//$("#city_country_id").val(country);
		$("#iso_country_code").val(iso_country_code);
		initAutocomplete();
		$('#city').autocomplete({
			
    change: function(event, ui) {
   if (ui.item == null){ 
             //here is null if entered value is not match in suggestion list
                $(this).val((ui.item ? ui.item.id : ""));
            }
    
}
	});
	
	$("#sup_submit").click(function(){
// alert('test');
		var country_id = $("#country_id").val();
		var city_name = $("#city").val();
//alert(country_id);
//alert(city_name);
		var loca=$("#full_city").val();
		//alert(loca);
		if(country_id!=''&&city_name!=''){
			$.ajax({
			url:"<?php echo base_url()?>"+"index.php/branch_users/unique_city_name",
			type: "post",
			data: {country_id : country_id, city_name : city_name, id : is_edit_profile},
			success:function(res){
				if(res==1){
					alert("This City Name Already Exists in this system");
				}else{
					// alert("else");
					// return false;
					$("#city_form").submit();
				}
			},
			error:function(res){
				alert("Technical Issues");
			}

			});
		}else{
			alert("Enter the city name details");
		}
		return false;
	});
});

var iso_country_code = $("#country_id").find("option:selected").data('iso-code');
$("#iso_country_code").val(iso_country_code);

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
         var autocomplete_val = document.getElementById('city');
         var options = {
             types: ['(cities)'],             
             componentRestrictions: {country: iso_country}
            };
         autocomplete = new google.maps.places.Autocomplete(autocomplete_val,options);

        autocomplete.addListener('place_changed', fillInAddress);
     
      }

      function fillInAddress() {     
        var place = autocomplete.getPlace();
        console.log(place);
        var address_components = place.address_components;
       console.log('view data');
console.log(place.geometry.viewport);
        var lat = place.geometry.location.lat();
        var lon = place.geometry.location.lng();
        
        var south = place.geometry.viewport.ma.j; // south //top
        var west = place.geometry.viewport.ga.j; // west //right

        var north = place.geometry.viewport.ma.l; // north //bottom
        var east =place.geometry.viewport.ga.l; // east // left
        var bounds = {
        	South:south,West:west,North:north,East:east
        };
        console.log(bounds);

        $("#lat").val(lat);
        $("#lng").val(lon);
        $("#bound").val(JSON.stringify(bounds));
        //console.log(place);
        var full_address=place.formatted_address;
        // alert(place.vicinity);
        if(typeof(place.vicinity)!='undefined' ){
        	var city_name = place.vicinity;
	        $("#full_city").val(full_address);
	        $("#city").val(city_name);

        }else{

        	var types =place.types;
        	// alert(types);
        	//for (var i = 0; i < types.length; i++) {
        		for (var j = address_components.length - 1; j >= 0; j--) {
        			for (var i = 0; i < address_components[j]['types'].length; i++) {
        				if($.inArray(address_components[j]['types'][i]),types){

        					$("#city").val(address_components[j].long_name);

        				}
        			}
        		}

}
}
       // $("#sup_submit").show();
     
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEfJ5CKkASY4yTMzOedGUFWKX4kgao03M&libraries=places&callback=initAutocomplete"
        async defer></script> 