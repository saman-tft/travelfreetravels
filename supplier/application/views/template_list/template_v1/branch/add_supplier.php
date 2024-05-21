<div id="Package" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<h1>Add Branch Users</h1>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<form action="<?php echo base_url(); ?>index.php/branch_users/add_branch"
							method="post" enctype="multipart/form-data"
							class='form form-horizontal validate-form'>
			<div class="tab-content">
				<!-- Add Package Starts -->
				<div role="tabpanel" class="tab-pane active" id="">
				
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>								Supplier Name </label>
								<div class='col-sm-4 controls'>
									<div class="controls">
										<input type="text" name="supplier_name" id="name"
											data-rule-required='true' placeholder="Enter Supplier Name"
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
								<label class='control-label col-sm-3' for='adult'>Email</label>
								<div class='col-sm-4 controls'>
									<input type="email" name="email" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Email"
										class='form-control add_pckg_elements' required="">
								</div>
							</div>	
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Password</label>
								<div class='col-sm-4 controls'>
									<input type="password" name="password" id="password"
										data-rule-number="true" data-rule-required='true' placeholder="Password"
										class='form-control add_pckg_elements'  required="">
								</div>
							</div>	
							


							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Phone No</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="phone_no" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Phone No"
										class='form-control add_pckg_elements numeric' maxlength='11' minlength='3' required="">
								</div>
							</div>	

							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>PAN No</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="pan_no" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Pan card number"
										class='form-control add_pckg_elements' maxlength='30' minlength='3' required="">
								</div>
							</div>			
							<!-- <div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Aadhaar Number</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="adhar_no" id="p_price"
										data-rule-number="true" data-rule-required='true' placeholder="Aadhaar Number"
										class='form-control add_pckg_elements numeric' maxlength='30' minlength='3' required="">
								</div>
							</div>	 -->
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_country'>Country</label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										data-rule-required='true' name='country' id="country" required>
										<!--  <input type="text" name="country" id="country" data-rule-required='true' class='form-control'>  -->
										<option value="">Select Location</option>
				                        <?php foreach ($country as $coun) {?>
				                        <option value='<?php echo $coun->country_id; ?>'><?php echo $coun->name; ?></option>
				                        <?php }?>
				                      </select>
								</div>
							</div>
							<div class='form-group' id="city_name_div">
								<label class='control-label col-sm-3' for='validation_current'>City
								</label>
								<div class='col-sm-4 controls'>
									<select class='select2 form-control add_pckg_elements'
										name='cityname' id="cityname" multiple="multiple" required>
										<option value=''>Select city</option>
									</select>
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
								<label class='control-label col-sm-3' for='validation_current'>Location
								</label>
								<div class='col-sm-4 controls'>
									<input type="text" name="location" id="location" placeholder="Enter Location"
										data-rule-required='true'
										class='form-control add_pckg_elements' required>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_company'>Supplier 
									Id Proof</label>
								<div class='col-sm-4 controls'>
									<input type="file" title='Image to add'
										class='add_pckg_elements' data-rule-required='true' id='photo'
										name='photo' required> <span id="pacmimg"
										style="color: #F00; display: none">Please Upload Package Image</span>
								</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='adult'>Address</label>
								<div class='col-sm-4 controls'>
									<textarea rows="4" cols="15" name="address" required=""></textarea>
								</div>
							</div>							
							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>
										
										<input class="btn btn-primary" type="submit" id=sup_submit value="Submit">&nbsp;&nbsp;
											<a class='btn btn-primary' href="<?php echo base_url(); ?>supplier/view_with_price"> Cancel</a>
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
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
 
     $(document).ready(function(){
         $('#country').on('change', function() {
           $.ajax({
           url: 'get_crs_city/' + $(this).val(),
           dataType: 'json',
           success: function(json) {
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