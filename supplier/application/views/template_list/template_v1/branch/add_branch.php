<head>



  <script src="http://demo.itsolutionstuff.com/plugin/croppie.js"></script>
  
  <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/croppie.css">
</head>
<div id="Package" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
			<?php

		
if(!empty($user_data))
{
	$id=$user_data['user_id'];
	?>
	<h1>Update Branch Users</h1>
	<?php
}
else
{
	$id=0;
			?>
				<h1>Add Branch Users</h1>
<?php

}
?>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<form action="<?php echo base_url(); ?>index.php/branch_users/add_branch/<?php echo $id;?>"
							method="post" enctype="multipart/form-data"
							class='form form-horizontal validate-form'>
			<div class="tab-content">
				<!-- Add Package Starts -->
				<div role="tabpanel" class="tab-pane active" id="">
				
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>	Branch Name <span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<div class="controls">
									<?php  
if(!empty($user_data['agency_name']))
{
	$v=$user_data['agency_name'];
}
else
{
$v=$user_data['agency_name'];	
}
									?>
										<input type="text" name="supplier_name" id="name"
											data-rule-required='true' value='<?php echo $v; ?>'placeholder="Enter Branch Name"
											class='form-control add_pckg_elements' required>
									</div>
								</div>
							</div>
						<!-- 	<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Company Reg No</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="reg_no" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Company Reg No"
										class='form-control add_pckg_elements' maxlength='30' minlength='3'required>
								</div>
							</div>	 -->
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Email<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<input value='<?php echo $user_data['email'];?>' type="email" name="email" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Email"
										class='form-control add_pckg_elements' required="" onkeypress="return emailkeypress(event);"  onblur="checkIsunique({table_name:'user',column_name:'email',field_value:this,label:'Email',hidden_field:'email_hidden_text',hidden_error:'email_hidden_text_error'});"

										 onmouseleave="checkIsunique({table_name:'user',column_name:'email',field_value:this,label:'Email',hidden_field:'email_hidden_text',hidden_error:'email_hidden_text_error'});">

									 	<input type="hidden" name="email_hidden_text" id="email_hidden_text">
										<span id="email_hidden_text_error" class="error"></span>

								</div>
							</div>	
							<?php
if(empty($user_data))
{
							?>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Password<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<input type="password" name="password" id="password"
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
	<input type="text" value="<?php echo $user_data['phone'];?>" name="phone_no" id="p_price" data-rule-number="true" data-rule-required='true' placeholder="Phone No" class='form-control add_pckg_elements numeric' maxlength='11' minlength='10' required="">
								</div>
							</div>	

							<!-- <div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>PAN No</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="pan_no" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Pan card number"
										class='form-control add_pckg_elements' maxlength='30' minlength='3' required="">
								</div>
							</div> -->			
							<!-- <div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Aadhaar Number</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="adhar_no" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Aadhaar Number"
										class='form-control add_pckg_elements numeric' maxlength='30' minlength='3' required="">
								</div>
							</div>	 -->
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
								<?php if(isset($user_data['city_list']))
								{?>
										<select class='select2 form-control add_pckg_elements'
											name='cityname' id="cityname" required>
											<option value=''>Select city</option>
											<?php foreach ($user_data['city_list'] as $citykey => $cityvalue) {?>
											<?php 
											$s_selected = '';
					                        if($city_origin==$cityvalue['city']){
					                        	$s_selected = "selected=selected";
					                        }
										?>
											<option value='<?=$cityvalue['id']?>' <?=$s_selected?>><?=$cityvalue['city']?></option>
												
											<?php } ?>
										</select>

								<?php }else{ ?>
								<?php
									if ((isset($city_origin)) && ($city_origin != "")) {
									 	$citydata = $this->db->get_where('car_active_country_city_master', array('origin' => $city_origin))->row_array();
									 	// debug($citydata);die;
									 } 
								 ?>
								<select class='select2 form-control add_pckg_elements'
											name='cityname' id="cityname" required>
											<?php if(isset($citydata['origin'])){ ?>
											<option value='<?=$citydata['origin']?>'><?=$citydata['city_name']?></option>
											<?php } else { ?>
											<option value=''>Select city</option>
											<?php } ?>
										</select>
									<?php }?>
								</div>
							</div>
							<!-- <div class='form-group'>
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
										data-rule-required='true' value='<?=$user_data["location"]?>'
										class='form-control add_pckg_elements' required>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_company'>Branch
									Id Proof <span style="color:red">*</span></label>
<?php 
										$m_required = 'required';
										if(isset($user_data['image'])){
											?>
											<a class='pull-left' style='margin-left:18px;' data-id="<?php echo $user_data['image']; ?>" data-toggle="modal" class="openimg" href="#openModal">
											
                    
					<img height="80px" width="120px" alt="" src="<?php echo get_host().DOMAIN_IMAGE_DIR.$user_data['image']?>">
											<?php
										}
										?>
										</a>
								
								<div class='col-sm-4 controls'>
									<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='<?php if(!isset($user_data['image'])){ echo "true"; }?>' id='photo'
										name='photo' <?php if(!isset($user_data['image'])){ echo "required"; }?>> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Package Image</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Address<span style="color:red">*</span></label>
								<div class='col-sm-4 controls'>
									<textarea rows="4" cols="15" name="address" required="" class="form-control"><?php echo trim($user_data['address']);?></textarea>
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
					</div>
				</div>
				<!-- Add PAckage Ends -->
			</div>
			</form>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
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


<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>

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
		    // orientation: 4
		});
	}, 500);
	
});    

$(document).on('click', '#croppieCrop', function (ev) {
	uploadCrop.result({
		type: 'canvas',
		size: 'viewport',
		showZoomer: false,
	    // enableOrientation: true,
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
	    // enableOrientation: true,
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



$('#country').on('change', function() {
	var _country =this.value;
	if (_country != 'INVALIDIP') {
		//load city for country
		$.get(app_base_url+'index.php/ajax/get_city_list/'+_country, function(resp) {
			$('#city').html(resp.data);
		});
	}
});
</script>

<script type="text/javascript">
 
     $(document).ready(function(){
        /* $('#country').on('change', function() {
         	//alert($(this).val());
         	var base_url='<?=base_url()?>';
           $.ajax({
           url: base_url+'/index.php/branch_users/get_crs_city/' + $(this).val(),
           dataType: 'json',
           success: function(json) {
           	// alert('in1');
           	if(json.result=='<option value="">Select City</option>'){
           		
           		$('#city_name_div').addClass('hide');
           	}
           	else{
           		
           		$('select[name=\'cityname\']').html(json.result);
           		('#city_name_div').removeClass('hide');
           	}
           }
       	});
         });
        $("#cityname").on('click',function(){
        	var dropdownVal=$(this).val();

        	$("#textbox").val(dropdownVal); 
		
    	});*/
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

	
     $('#country').on('click', function() {
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
  
     function show_duration_info(duration)
       {
	       if(duration=='')
	       {
	       	duration=0;
	       }
	       if (window.XMLHttpRequest)
	       {// code for IE7+, Firefox, Chrome, Opera, Safari
	      	xmlhttp=new XMLHttpRequest();
	       }
	       else
	       {// code for IE6, IE5
	      	 xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	       }
	      	 xmlhttp.onreadystatechange=function()
	       {
	       	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	       {
	      	 document.getElementById("duration_info").innerHTML=xmlhttp.responseText;
	       }
	       }
	      	 xmlhttp.open("GET","itinerary_loop/"+duration,true);
	      	 xmlhttp.send();
       }
     $("#addanother").click(function(){
     var addin = '<input type="text" name="ancountry" value="" placeholder="country" class="ma_pro_txt" style="margin:2px;"/><input type="text" name="anstate" placeholder="state" value="" class="ma_pro_txt" style="margin:2px;"/><input type="text" name="ancity" placeholder="city" value="" class="ma_pro_txt" style="margin:2px;"/><div onclick="removeinput()" style="font-weight:bold;cursor:pointer;">Remove</div><br/>';
     $("#addmorefields").html(addin);
  });
  
  function removeinput(){
   $("#addmorefields").html('');
  }
  
       function activate(that) { window.location.href = that; }
  var a;
  $(document).ready(function(){ 
  
  $('#addCityInput').click(function(){
   var cityNo = parseInt($('#multiCityNo').val());
    //alert(cityNo);
    var duration = $('#duration').val();
   var cityNo = cityNo+1;
    var cit = cityNo-1;
   var allCity = '';
   var i = cityNo;
   var s = i-1;
    
   allCity += "<div id='bothCityInputs"+i+"'><div class='form-group'><label class='control-label col-sm-2' for='validation_company'>From Date</label><div class='input-group col-sm-3' ><input class='fromd datepicker2 b2b-txtbox form-control' placeholder='MM/DD/YYYY' id='deptDate"+i+"'  myid='"+i+"' name='sd[]'' type='text'><span class='input-group-addon'><i class='icon-calendar'></i></span></div><label class='control-label col-sm-2' for='validation_name'>To Date</label><div class='input-group col-sm-3' ><input class='form-control b2b-txtbox' placeholder='MM/DD/YYYY' id='too"+i+"' name='ed[]'' type='text' readonly><span class='input-group-addon'><i class='icon-calendar'></i></span><span id='dorigin_error7' style='color:#F00;'></span><span id='dorigin_error' style='color:#F00;'></span><br></div><br></div>";
  
   allCity += "<div class='form-group clearfix'><label class='control-label col-sm-2' for='adult'>Adult Price</label><div class='input-group col-sm-3' ><input type='text' name='adult[]' id='adult"+i+"'  myid='"+i+"' data-rule-number='true' data-rule-required='true' class='form-control'><span class='input-group-addon'><i class='icon-usd'></i></span></div><label class='control-label col-sm-2' for='child'>Child Price</label><div class='input-group col-sm-3' ><input type='text' name='child[]' id='child"+i+"'  myid='"+i+"' data-rule-number='true' data-rule-required='true' class='form-control'><span class='input-group-addon'><i class='icon-usd'></i></span></div></div><hr>";
  allCity += '<script>var d1 = $("#deptDate'+cit+'").datepicker("getDate");'+
                 //'var dd = d1.getDate() + 1;var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                 'd1.setDate(d1.getDate() + parseInt(1));'+
                  'var dd = d1.getDate();var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                 'var to_date = (mm) + "/" + dd + "/" + yy;'+
                 //'var to_date = (mm) + "/" + dd + "/" + yy;'+
                 'alert(to_date);'+
                  'var duration = $("#duration").val();'+
                  '$("#deptDate'+i+'").datepicker({'+
                  'dateFormat: "mm/dd/yy",'+
                  'minDate: to_date,'+
                   'onSelect: function(dateStr) {'+
                    'var d1 = $(this).datepicker("getDate");'+  
                    
  
                    'd1.setDate(d1.getDate() + parseInt(duration));'+
                   
                     'var dd = d1.getDate();var mm = d1.getMonth() + 1;var yy = d1.getFullYear();'+
                     'var to_date = (mm) + "/" + dd + "/" + yy;'+
                     '$("#too'+i+'").val(to_date);'+
                       '}'+
                    '});'+
                       '<\/script>'+
                       '</div>';
   //$("#addMultiCity").append("<label class='control-label col-sm-2' for='validation_company'>From</label><div class='col-sm-3 controls'><input name='sd' id='' type='text' class='datepicker2 b2b-txtbox form-control'     />   <span id='dorigin_error6' style='color:#F00;'></span><br></div><label class='control-label col-sm-3' for='validation_name'>To</label><div class='col-sm-3 controls'><input name='ed' id='' type='text' class='datepicker3 b2b-txtbox form-control'   />  <span id='dorigin_error7' style='color:#F00;'></span><span id='dorigin_error' style='color:#F00;'></span></div>");
                         
   $("#addMultiCity").append(allCity);
   if(cityNo>1){
     $("#removeCityButton").show();
   }
   $('#multiCityNo').val(cityNo);
     });
  $('#removeCityInput').click(function(){
   var cityNo = parseInt($('#multiCityNo').val());
   
   var allCity = '';
   if(cityNo >1){
     $("#bothCityInputs"+cityNo).remove();
     var cityNo = cityNo-1;
     if(cityNo>1){
       $("#removeCityButton").show();
   }
   }
   else
      {
     $("#removeCityButton").hide();
      }
   $('#multiCityNo').val(cityNo);
  });  

$('#add_package_button').click(function(){
	var error_free = true;
	var value = CKEDITOR.instances['editor'].getData();
    $( ".add_pckg_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
    if(value == ''){
    	 error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
    }
      if(error_free)
      {
      	  $("#add_package_li").removeClass("active");
      	  $("#add_package").removeClass("active");
      	  $("#itenary_li").addClass("active");
      	  $("#itenary").addClass("active");
      }
    

    ///  alert(value);
	  });
$('#itenary_button').click(function(){
	var error_free = true;
    $( ".itenary_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
	  $("#itenary_li").removeClass("active");
	  $("#itenary").removeClass("active");
	  $("#gallery_li").addClass("active");
	  $("#gallery").addClass("active");
      }
	  });
$('#gallery_button').click(function(){
	var error_free = true;
    $( ".gallery_elements" ).each(function() {
        if($( this ).val() == ''){
          error_free = false;
          $( this ).closest( ".form-group" ).addClass( "has-error" );
        }        
      });
      if(error_free)
      {
	  $("#gallery_li").removeClass("active");
	  $("#gallery").removeClass("active");
	  $("#rate_card_li").addClass("active");
	  $("#rate_card").addClass("active");
      }
	  });
  });
    $(document).ready(function(){ 
  
  $(document).on("change",".fromd",function(){ 
     current_date = $(this).val();
     
   current_id = $(this).attr('id');
   // alert(current_id);
  $(".fromd").each(function(){ 
     previous_dates = $(this).val();
      //alert(previous_dates);
     currenr_id=$(this).attr('id');
  
      
     if(current_date == previous_dates && current_id != currenr_id){
   myid=$("input[type='text']#"+current_id).attr('myid');
     alert("Already Same Date Selected");
     $("#"+current_id).val(" ");
    // alert(myid);
      $("#to"+myid).val(" ");
        $("#too"+myid).val(" ");
  }
   });
  });
  });
  
    $('#validation_country').on('change', function(){
        var country=$(this).val();
        $.ajax({
            type:"POST",
            url: "<?php echo base_url(); ?>supplier/get_cities/"+country,
            data:{country:country},
            success:function(wcity)
            {
            //	alert(wcity);
              $('#city').html(wcity);
            }
          });
      });
</script>