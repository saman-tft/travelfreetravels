
<style type="text/css">
  .append_form {
    float: left;
    width: 100%;
}
  .btpd{margin-bottom:10px;}

.lablform{
  color:#1482c9;
  font-weight: bold;}

</style>
<script>
    $(document).ready(function(){
    var date = new Date();
    date.setDate(date.getDate()+1);
    //alert(date);
    
    $("#dob_cal0").datepicker({
      changeMonth:true,
      changeYear:true,
      yearRange: "c-100:date",
      maxDate:date
            
        });
    $("#expiry_date0").datepicker({
            changeMonth:true,
            changeYear:true,
            minDate:date,
        });
    
    for(var i=1; i<11; i++)
    {
      $("#dob_cal"+i).datepicker({
      changeMonth:true,
      changeYear:true,
      yearRange: "c-100:date",
      maxDate:date
            
        });
      $("#expiry_date"+i).datepicker({
            changeMonth:true,
            changeYear:true,
            minDate:date
        });
    }
  
    $("#travel_date").datepicker({
      changeMonth:true,
      changeYear:true,
      minDate:date
    });
    
        
});
</script>
<div class="tab-pane  active" id="visa">
  <div id="Package" class="bodyContent col-md-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="panel-title">
        <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
          <li role="presentation" class="active" id="add_package_li">
      <a href="#add_package" aria-controls="home" role="tab" data-toggle="tab">Add VISA</a>
     </li><br> 
    </ul>
   </div>
  </div>
</div>
</div> 
<form autocomplete="on" name="bus" enctype="multipart/form-data" id="bus_form" action="<?=base_url();?>index.php/visa/visa_data" method="post" siq_id="autopick_8696">
<div class="tabspl visa_srch1">
<div class="col-xs-12 nopad">
<div class="org_row">
<!-- <div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Ref. No<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<input type="text" placeholder="Ref. No" id="ref_no" class="form-control visaInput" required="required" name="ref_no" value="<?=$ref_no?>" readonly ></div>
</div> -->
<div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">Nationality<span class="text-danger">*</span></div>
<div class="selectedwrap sidebord">
<select name="nationality" class="form-control visaInput" id="nationality" required="required" onchange="changetype(0);">
<option value="">Nationality</option>
<?php foreach ($country_data as $value) { ?>
<option value="<?=$value['country_name']?>"><?=$value['country_name']?></option>
<?php } ?>
</select>
</div></div>
<input type="hidden" id="gst" name="gst">
<input type="hidden" id="convenience_fee" name="convenience_fee">
<input type="hidden" id="markup" name="markup">
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Email ID<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<input type="email" placeholder="Email" id="email1" class="search_filter form-control visaInput email" required="required" name="email" maxlength="50"></div></div>
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Phone No<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<input type="text" placeholder="Phone No" id="mob_no" class="form-control visaInput fulwishxl numeric" required="required" name="phone" maxlength="12" minlength="10"></div></div>
<div id="visa_info">
<div class="clearfix add_more">
<div class="col-xs-12 nopad" id="user_fare_details0" style="display: none;"><br>
<span id="user_fare0" style="color: red; font-weight: bold;font-size: 25px;"> </span><br>
<span id="user_convenience_fee0" style="color: red; font-weight: bold;font-size: 25px;"> </span><br>
<span id="user_markup_fee0" style="color: red; font-weight: bold;font-size: 25px;"> </span><br>
<span id="user_total0" style="color: red; font-weight: bold;font-size: 25px;"> </span>
</div>
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">First Name<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<input type="text" placeholder="First Name" id="fname[0]" class="form-control visaInput fname_val" required="required" name="fname[0]"></div>
</div>
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Middle Name</div>
<div class="plcetogo sidebord"><input type="text" placeholder="Middle Name" id="mname[0]" class="form-control visaInput fname_val" name="mname[0]"></div></div>
<div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">Last Name<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<input type="text" placeholder="Last Name" id="lname[0]" class="form-control visaInput fname_val" required="required" name="lname[0]"></div></div>
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Date of Birth<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<input type="text" id="dob_cal0" placeholder="Date of Birth" class="form-control visaInput mydob" required="required" readonly name="dob[0]"><input type="hidden" id="age0" name="age[0]"></div></div>
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Address<span class="text-danger">*</span></div>
<div class="plcetogo sidebord"><textarea class="form-control visaInput" placeholder="Address" name="address[0]" required="required"></textarea>
</div>
</div>
<div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">VISA Applying Country<span class="text-danger">*</span>
</div>
<div class="selectedwrap sidebord">
<select name="applying_country[0]" class="form-control visaInput" id="to_country_origin0" onchange="changetype_visa(0);repeat_visa_country();" required="required">
<option value="">VISA Applying Country</option>
<?php foreach ($country_data as $value) { ?>
<option value="<?=$value['country_name']?>"><?=$value['country_name']?></option>
<?php } ?>
</select>
</div>
</div>

<!-- <div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Travelling Date</div>
<div class="plcetogo sidebord">
<input type="text" id="travel_date" placeholder="Travel Date" class="form-control visaInput hasDatepicker" required="required" name="travel_date"></div>
</div> -->
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Passport No<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<input type="text" placeholder="Passport No" id="passport_no[0]" class="form-control visaInput passportnumber" required="required" name="passport_no[0]"></div></div>
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Passport Name<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<input type="text" placeholder="Passport Name" id="passport_name[0]" class="form-control visaInput fname_val" required="required" name="passport_name[0]"></div></div>
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Passport Expiry Date<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<input type="text" placeholder="Expiry Date" id="expiry_date0" class="form-control visaInput" required="required" readonly name="expiry_date[0]"></div></div>
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">VISA Type<span class="text-danger">*</span></div>
<div class="selectedwrap sidebord">
<select name="visa_type[0]" class="form-control visaInput" id="visa_type0" onchange="get_visa_type(this.value);" required="required">
<option value="">VISA Type</option>
<option value="Business">Business</option>
<option value="Tourist">Tourist</option>
<option value="Student">Student</option>
<option value="Other">Other</option>
</select>
</div>
</div>
<div class="col-sm-3 col-xs-6 padd full_mobile" id="other0" style="display: none;">
<div class="lablform">&nbsp</div>
<div class="plcetogo sidebord">
<input type="text" placeholder="Enter VISA Type" id="other_type0" class="form-control visaInput" name="other_type[0]" onchange="get_faredetails(0)"></div></div>
<div class="col-sm-4 col-xs-6 padd full_mobile">
<div class="lablform">Passport Image<span class="text-danger">*</span></div>
<div class="selectedwrap sidebord">
<input type='file' id="upload-photo_0"  name="story_image[0]" class="upload-photo_0" onchange="readURL(this,0);"  required="required" />
<p id="message1" style="color:#FF0000;">
Image Format Must Be JPG, JPEG, PNG, DOC or PDF. <br>
Maximum File Size Limit is 1MB.
</p>
</div>
</div>
<div class="create_posts_section_0">
<div class="files_uploads_story_0">
                       
                        <input type='text' id="upload-photo_hidden"  name="passport_image_hidden[0]"  class="hidden"/>
                        <input type="hidden" class="removed_images_0" id="removed_images_0" name="removed_images[0]  "/>
                        <label id="upload-photo-label_0" for="upload-photo_0">
                            <!-- <span class="cam_1"><i class="fa fa-camera"></i></span> -->
                  <!-- <span class="cam_2"><i class="fa fa-video-camera"></i></span> -->
                        </label>
                      </div>   
          
<div id="img_view_0" class="col-md-2 thumbnail hide">
                    </div>
            </div>
<div class="col-sm-4 col-xs-6 padd full_mobile">
 <div class="lablform">ID Proof<span class="text-danger">*</span></div>
<div class="selectedwrap sidebord">
<input type='file' id="upload-id_0"  name="id_image[0]" class="upload-id_0" onchange="readURL_id(this,0);"  required="required"/>
<p id="message1" style="color:#FF0000;">
Image Format Must Be JPG, JPEG, PNG, DOC or PDF. <br>
Maximum File Size Limit is 1MB.
</p>
</div>
</div>
<div class="posts_section_0">
<div class="uploads_story_0">
                       
                        <input type='text' id="photo_hidden"  name="id_image_hidden[0]"  class="hidden"/>
                        <input type="hidden" class="remove_images_0" id="remove_images_0" name="remove_images[0]  "/>
                        <label id="upload-phot-label_0" for="upload-phot_0">
                            <!-- <span class="cam_1"><i class="fa fa-camera"></i></span> -->
                  <!-- <span class="cam_2"><i class="fa fa-video-camera"></i></span> -->
                        </label>
                      </div>   
          
<div id="imag_view_0" class="col-md-2 thumbnail hide">
                    </div>
            </div>
</div>
</div>
<div class="col-xs-12 nopad"><br>
<div class="addDiscountBox"><span class="btn btn-info pull-right addDiscountBtn">Add Form</span></div>
</div>
<!-- <div class="col-sm-3 col-xs-6 padd full_mobile" id="other" style="display: none;">
<div class="lablform"></div>
<div class="plcetogo sidebord">
<input type="text" placeholder="Enter Visa Type" id="other_type" class="form-control visaInput" name="other_type" disabled></div></div> -->
<div class="col-sm-12 col-xs-6 padd full_mobile">
<div class="lablform">Reason (Purpose of visit)</div>
<div class="plcetogo sidebord">
<textarea placeholder="Reason" id="reason" class="form-control visaInput ckeditor" required="required" name="reason"></textarea></div></div>

<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Duration of Stay<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<select id="duration" name="duration" class="form-control" onchange="get_duration(this.value);" required>
<option value="">Duration of Stay</option>
  <option value="1 week">1 week</option>
  <option value="15 days">15 days</option>
  <option value="1 month">1 month</option>
  <option value="3 months">3 months</option>
  <option value="6 months">6 months</option>
  <option value="9 months">9 months</option>
  <option value="1 year">1 year</option>
  <option value="Other">Other</option>
</select>
</div></div>
<div class="col-sm-3 col-xs-6 padd full_mobile" id="others" style="display: none;">
<div class="lablform"> &nbsp;</div>
<div class="plcetogo sidebord">
<input type="text" placeholder="Enter Year" id="no_of_years" class="form-control visaInput" name="no_of_years"></div></div>
<div class="col-sm-3 col-xs-6 padd full_mobile">
<div class="lablform">Date of Travel<span class="text-danger">*</span></div>
<div class="plcetogo sidebord">
<input type="text" placeholder="Travel Date" id="travel_date" class="form-control visaInput" required="required" readonly name="travel_date"></div></div>
<div class="col-sm-12 col-xs-6 padd full_mobile">
<div class="lablform">Other Details</div>
<div class="plcetogo sidebord">
<textarea placeholder="Other Details" id="other_detail" class="form-control visaInput ckeditor" name="other_detail"></textarea></div></div>


</div></div>
 
<div class="col-xs-12 nopad btpd">
<div class="org_row">
<div class="col-sm-1 col-xs-6 padd full_mobile"><div class="lablform">&nbsp;</div><div class="searchsbmtfot">
<input type="submit" id="visa_validation" float="center" class="searchsbmt btn btn-primary" value="Submit">
</div></div>
<div class="col-sm-1 col-xs-6 padd full_mobile"><div class="lablform">&nbsp;</div>
<a href="<?=base_url().'index.php/visa/view_visa'?>" id="back_button" class="btn btn-warning">Back</a>
</div>
</div></div></div>
</form>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY?>/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
 
        $('#upload').submit(function(e){
            e.preventDefault(); 
          
                 $.ajax({
                     url:'<?php echo base_url();?>index.php/visa/do_upload',
                     type:"post",
                     data:new FormData(this),
                     processData:false,
                     contentType:false,
                     cache:false,
                     async:false,
                      success: function(data){
                        alert("Upload Image Successful.");
                        $("#image_div").html(data);
                          
                   }
                 });
            });
         
 
    });
    function get_details(id,visa_id)
    {
      //alert(visa_id);
      if(confirm("Are you sure you want to delete this record?"))
      {
       $.ajax({
                     url:'<?php echo base_url();?>index.php/visa/delete_image/'+id+'/'+visa_id,
                     type:"post",
                     data:new FormData(this),
                     processData:false,
                     contentType:false,
                     cache:false,
                     async:false,
                      success: function(data){
                        $("#image_div").html(data);
                         
                   }
                 });
      }
    }
     
</script>
<script type="text/javascript">
  $(function(){
    $('.people_like_sections').slimScroll({
      height: 483,
    });
    if($('.all_rplies_sec.not_null > .all_rplies').length>1){
      $('.all_rplies_sec.not_null').slimScroll({
        height: 120,
      });
    } 
  }); 
    var keyCounter = 0;
    var removed_images=[];
    var i=0;
    var j=0;
    function readURL(input,k){ 
        $("#img_view_"+k).show(''); 
        $('#img_view_'+k).removeClass('hide');
        $('#img_view_'+k+'.err_msg').remove();       
        $('#img_view_'+k).html('');
        var files_array= input.files;
        var img='';        
        var reader;
        $.each( files_array, function( key, value ) {
          j=j+1;          
          var image_name=files_array[key]['name'];          
          key=keyCounter+key;
            if(value['size']<=15000000){
              if(value['type']=='pdf/doc/docx'){
                img = '<div id=" uploaded_image_'+key+'" class="img_preview_sec"><video id="blah_'+key+'" ><source src="#"></video><i class="fas fa-times" onclick="remove_up_image(\''+image_name+'\','+key+','+j+','+k+')"  ></i></div>';
            }
            else{
                img = '<div id="uploaded_image_'+key+'" class="img_preview_sec"><img style="width: 150px; height: 200px;" id="blah_'+key+'" src="#"/><i class="fas fa-times" onclick="remove_up_image(\''+image_name+'\','+key+','+j+','+k+')"></i></div>';
              }
              $('#img_view_'+k).append(img);
              reader = new FileReader();
              reader.readAsDataURL(input.files[key-keyCounter]);
              reader.onload = function (e) {
                $('#img_view_'+k).find('#blah_'+key)
                .attr('src', e.target.result)
                .width(150)
                .height(200);
              };
          }
      if((files_array.length-1)==(key-keyCounter)){
        keyCounter = key+1;
      }
      if(value['size']>=15000000){
        console.log(value['name'])
        console.log(key);
        console.log(j);
        alert("File Upload Limit 15Mb");
        console.log(files_array); 
        remove_up_image(image_name,key,j);
      }
        });
        i=i+1;
        $('#upload-photo-label'+k).attr('for','upload-photo_'+k+i);
        var html="<input type='file' id='upload-photo_"+k+i+"'  name='story_image_"+i+"[]'  class='hidden upload-photo_"+k+i+"' onchange='readURL(this,k);'/>";
        $('.create_posts_section'+k).find('.files_uploads_story'+k).append(html);
    }
    function readURL_id(input,k){ 
        $("#imag_view_"+k).show(''); 
        $('#imag_view_'+k).removeClass('hide');
        $('#imag_view_'+k+'.err_msg').remove();
        $('#imag_view_'+k).html('');
        var files_array= input.files;
        var img='';        
        var reader;
        $.each( files_array, function( key, value ) {
          j=j+1;          
          var image_name=files_array[key]['name'];          
          key=keyCounter+key;
            if(value['size']<=15000000){
              if(value['type']=='pdf/doc/docx'){
                img = '<div id=" uploaded_image_'+key+'" class="img_preview_sec"><video id="blah_'+key+'" ><source src="#"></video><i class="fas fa-times" onclick="remove_up_image_id(\''+image_name+'\','+key+','+j+','+k+')"  ></i></div>';
            }
            else{
                img = '<div id="uploaded_image_'+key+'" class="img_preview_sec"><img style="width: 150px; height: 200px;" id="blah_'+key+'" src="#"/><i class="fas fa-times" onclick="remove_up_image_id(\''+image_name+'\','+key+','+j+','+k+')"></i></div>';
              }
              $('#imag_view_'+k).append(img);
              reader = new FileReader();
              reader.readAsDataURL(input.files[key-keyCounter]);
              reader.onload = function (e) {
                $('#imag_view_'+k).find('#blah_'+key)
                .attr('src', e.target.result)
                .width(150)
                .height(200);
              };
          }
      if((files_array.length-1)==(key-keyCounter)){
        keyCounter = key+1;
      }
      if(value['size']>=15000000){
        console.log(value['name'])
        console.log(key);
        console.log(j);
        alert("File Upload Limit 15Mb");
        console.log(files_array); 
        remove_up_image(image_name,key,j);
      }
        });
        i=i+1;
        $('#upload-phot-label'+k).attr('for','upload-phot_'+k+i);
        var html="<input type='file' id='upload-phot_"+k+i+"'  name='id_image_"+i+"[]'  class='hidden upload-phot_"+k+i+"' onchange='readURL_id(this,k);'/>";
        $('.posts_section'+k).find('.uploads_story'+k).append(html);
    }
    function remove_up_image(file_name,key,len,k){
         $("#img_view_"+k).html('');
         $("#img_view_"+k).hide('');
        // $("div").remove('#uploaded_image_'+k+key);
        //   if($("#img_view_"+k).children().length<1)
        //   {
        //     $("#upload-photo_"+k).val('');
        //     $('#img_view_'+k).addClass('hide');
        //   }
        //   j=j-1;          
        //   removed_images.push(file_name);

        //   $('.create_posts_section'+k).find('.removed_images_'+k).val(JSON.stringify(removed_images));
    }
    function remove_up_image_id(file_name,key,len,k){  
        $("#imag_view_"+k).html('');
        $("#imag_view_"+k).hide('');   
        // $("div").remove('#uploaded_image_'+k+key);
        //   if($("#img_view_"+k).children().length<1)
        //   {
        //     $("#upload-photo_"+k).val('');
        //     $('#img_view_'+k).addClass('hide');
        //   }
        //   j=j-1;          
        //   removed_images.push(file_name);

        //   $('.create_posts_section'+k).find('.removed_images_'+k).val(JSON.stringify(removed_images));
    }
    </script>


 <style type="text/css">
                     #drop-zone {
  width: 100%;
  min-height: 150px;
  border: 3px dashed rgba(0, 0, 0, .3);
  border-radius: 5px;
  font-family: Arial;
  text-align: center;
  position: relative;
  font-size: 20px;
  color: #7E7E7E;
}
#drop-zone input {
  position: absolute;
  cursor: pointer;
  left: 0px;
  top: 0px;
  opacity: 0;
}
/*Important*/

#drop-zone.mouse-over {
  border: 3px dashed rgba(0, 0, 0, .3);
  color: #7E7E7E;
}
/*If you dont want the button*/

#clickHere {
  display: inline-block;
  cursor: pointer;
  color: white;
  font-size: 17px;
  width: 150px;
  border-radius: 4px;
  background-color: #4679BD;
  padding: 10px;
}
#clickHere:hover {
  background-color: #376199;
}
#filename {
  margin-top: 10px;
  margin-bottom: 10px;
  font-size: 14px;
  line-height: 1.5em;
}
.file-preview {
  background: #ccc;
  border: 5px solid #fff;
  box-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
  display: inline-block;
  width: 60px;
  height: 60px;
  text-align: center;
  font-size: 14px;
  margin-top: 5px;
}
.closeBtn:hover {
  color: red;
}
.img_preview_sec img {
    width: 115px!important;
    height: 100px!important;
}
.img_preview_sec i.fa-times {
    position: absolute;
    right: 3px;
    top: 3px;
    color: #c00000;
    cursor: pointer;
}
.img_preview_sec {
    padding-left: 0;
    display: inline-block;
    position: relative;
}
.add_more {
    margin-top: 15px;
    float: left;
    width: 100%;
    background: #f5f5f5;
    padding: 15px;
    border: 1px solid #ddd;
}
</style>
<script type="text/javascript">
  var i=1;
      $('body').delegate(".addDiscountBtn", "click", function(){
        if(i>10)
        {
          alert('Maximum customer added is 10');
        }
        else
        {
        var iterateMe = <?=json_encode($country)?>;    
        var cntry = $('#to_country_origin0').val();
        //console.log(iterateMe);
        var urlbox = '';
        urlbox='<div class="visa_info'+i+'"><div class="clearfix add_more"><div class="append_form"><div class="col-xs-12 nopad" id="user_fare_details'+i+'" style="display: none;"><br><span id="user_fare'+i+'" style="color: red; font-weight: bold;font-size: 25px;"> </span><br><span id="user_convenience_fee'+i+'" style="color: red; font-weight: bold;font-size: 25px;"> </span><br><span id="user_markup_fee'+i+'" style="color: red; font-weight: bold;font-size: 25px;"> </span><br><span id="user_total'+i+'" style="color: red; font-weight: bold;font-size: 25px;"> </span><input type="hidden" id="gst'+i+'" name="age['+i+']"><input type="hidden" id="convenience_fee'+i+'" name="age['+i+']"></div><div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">First Name<span class="text-danger">*</span></div><div class="plcetogo sidebord"><input type="text" placeholder="First Name" id="fname['+i+']" class="form-control visaInput fname_val1" required="required" name="fname['+i+']"></div></div><div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">Middle Name</div><div class="plcetogo sidebord"><input type="text" placeholder="Middle Name" id="mname['+i+']" class="form-control visaInput fname_val1" name="mname['+i+']"></div></div><div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">Last Name<span class="text-danger">*</span></div><div class="plcetogo sidebord"><input type="text" placeholder="Last Name" id="lname['+i+']" class="form-control visaInput fname_val1" required="required" name="lname['+i+']"></div></div><div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">Date of Birth<span class="text-danger">*</span></div><div class="plcetogo sidebord"><input type="text" id="dob_cal'+i+'" placeholder="Date of Birth" class="form-control visaInput mydob" readonly required="required" name="dob['+i+']"><input type="hidden" id="age'+i+'" name="age['+i+']"></div></div><div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">Address<span class="text-danger">*</span></div><div class="plcetogo sidebord"><textarea class="form-control visaInput " placeholder="Address" name="address['+i+']" required="required"></textarea></div></div><div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">VISA Applying Country<span class="text-danger">*</span></div><div class="selectedwrap sidebord"><input type="text" name="applying_country['+i+']" class="form-control visaInput visa_cntry" id="to_country_origin'+i+'" onchange="changetype_visa('+i+');" required="required" value="'+cntry+'" disabled></div></div><div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">Passport No<span class="text-danger">*</span></div><div class="plcetogo sidebord"><input type="text" placeholder="Passport No" id="passport_no['+i+']" class="form-control visaInput passportnumber1" required="required" name="passport_no['+i+']"></div></div><div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">Passport Name<span class="text-danger">*</span></div><div class="plcetogo sidebord"><input type="text" placeholder="Passport Name" id="passport_name['+i+']" class="form-control visaInput fname_val1" required="required" name="passport_name['+i+']"></div></div><div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">Passport Expiry Date<span class="text-danger">*</span></div><div class="plcetogo sidebord"><input type="text" placeholder="Expiry Date" id="expiry_date'+i+'" class="form-control visaInput" required="required" readonly name="expiry_date['+i+']"></div></div><div class="col-sm-3 col-xs-6 padd full_mobile"><div class="lablform">VISA Type<span class="text-danger">*</span></div><div class="selectedwrap sidebord"><select name="visa_type['+i+']" class="form-control visaInput" id="visa_type'+i+'" onchange="get_visa_type_detls(this.value,'+i+');" required="required"><option value="">VISA Type</option><option value="Business">Business</option><option value="Tourist">Tourist</option><option value="Student">Student</option><option value="Other">Other</option></select></div></div><div class="col-sm-3 col-xs-6 padd full_mobile" id="other'+i+'" style="display: none;"><div class="lablform">&nbsp</div><div class="plcetogo sidebord"><input type="text" placeholder="Enter VISA Type" id="other_type'+i+'" class="form-control visaInput" name="other_type['+i+']" onchange="get_faredetails('+i+')"></div></div><div class="col-sm-4 col-xs-6 padd full_mobile"><div class="lablform">Passport Image<span class="text-danger">*</span></div><div class="selectedwrap sidebord"><input type="file" id="upload-photo_'+i+'"  name="story_image['+i+']" class="upload-photo_0" onchange="readURL(this,'+i+');"  /><p id="message1" style="color:#FF0000;">Image Format Must Be JPG, JPEG, PNG, DOC or PDF. <br>Maximum File Size Limit is 1MB.</p></div></div><div class="create_posts_section_0"><div class="files_uploads_story_0">                                               <input type="text" id="upload-photo_hidden"  name="passport_image_hidden['+i+']"  class="hidden"/>                        <input type="hidden" class="removed_images_'+i+'" id="removed_images_'+i+'" name="removed_images['+i+']  "/>                        <label id="upload-photo-label'+i+'" for="upload-photo_0">                         </label>                      </div>             <div id="img_view_'+i+'" class="col-md-2 thumbnail hide">                    </div>            </div><div class="col-sm-4 col-xs-6 padd full_mobile"> <div class="lablform">ID Proof<span class="text-danger">*</span></div><div class="selectedwrap sidebord"><input type="file" id="upload-id_'+i+'"  name="id_image['+i+']" class="upload-id_0" onchange="readURL_id(this,'+i+');"  /><p id="message1" style="color:#FF0000;">Image Format Must Be JPG, JPEG, PNG, DOC or PDF. <br>Maximum File Size Limit is 1MB.</p></div><div class="posts_section_'+i+'"><div class="uploads_story_'+i+'">                                               <input type="text" id="photo_hidden"  name="id_image_hidden['+i+']"  class="hidden"/>                        <input type="hidden" class="remove_images_'+i+'" id="remove_images_'+i+'" name="remove_images['+i+']  "/>                        <label id="upload-photo-label_'+i+'" for="upload-photo_'+i+'">                        </label>                      </div>             <div id="imag_view_'+i+'" class="col-md-2 thumbnail hide">                    </div>            </div></div></div>';       
         urlbox += '<div class="deleteBox"><span class="btn btn-info pull-right delete_url_Btn"  data-myval="'+i+'">Delete Form</span></div></div></div></div></div></div>';

      
    $('#visa_info').append(urlbox);
    date_details(i);
    i++;
  
    $('#i_value').val(i);

    }
    

  });

      $('body').delegate(".delete_url_Btn", "click", function(){
    
    var count_val=$(this).data('myval');
    $(".visa_info"+count_val).remove();
    //alert(count_val); 
  });

  function date_details(id)
  {
     var date = new Date();
    date.setDate(date.getDate()+1);
    $("#dob_cal"+id).datepicker({
      changeMonth:true,
      changeYear:true,
      yearRange: "c-100:date",
      maxDate:date
            
        });
    $("#expiry_date"+id).datepicker({
            changeMonth:true,
            changeYear:true,
            minDate:date
        });
      var $dateInput = $('#dob_cal'+id);
      $dateInput.on("change", function () {
           dob = new Date($(this).val());
          var today = new Date();
          var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
          $('#age'+id).val(age);
          $('#visa_type'+id).val('');
          $('#user_fare'+id).html('');
          $('#user_fare_details'+id).hide();
         //your code
      });

      

  }
  function get_visa_type_detls(visa_type,id)
    {
       if(visa_type=='Other')
       {
         $("#other"+id).show();
         $("#other_type"+id).attr("required");
         $("#other_type"+id).removeAttr("disabled");
         $('#user_fare'+id).html('');
         $('#user_fare_details'+id).hide();
       }
       else
       {
         $("#other"+id).hide();
         $("#other_type"+id).removeAttr("required");
         $("#other_type"+id).attr("disabled");
         get_faredetails(id);
       }
    }
      function get_faredetails(id)
      {
       var nationality = $("#nationality").val();
       if(nationality=='')
       {
        alert('Please Select Nationality');
        return false;
       }
       var applying_country = $("#to_country_origin"+id).val();
       if(applying_country=='')
       {
        alert('Please Select VISA Applying Country');
        return false;
       }
       var dob_cal = $("#dob_cal"+id).val();
       if(dob_cal=='')
       {
        alert('Please Select Date of Birth');
        return false;
       }
       var visa_type = $("#visa_type"+id).val();
       var visa_other = $("#other_type"+id).val();
       var age = $("#age"+id).val();
       // var corencyicon="<?php echo get_application_default_currency();?>";
        $.ajax({
                     url:'<?php echo base_url();?>index.php/visa/get_fare_details',
                     type:"post",
                     data:{nationality:nationality,applying_country:applying_country,visa_type:visa_type,age:age,visa_other:visa_other},
                      success: function(data){
                        var result=data.split(',');
                        var corencyicon=result[0];
                        var fare=result[1];
                        var gst=result[2];
                        var convenience_fee=result[3];                     
                        var markup=result[4];                     
                        // var percent = (gst / 100) * fare+'.00';
                        var percent = gst;
                        var faregst=parseFloat(result[1])+parseFloat(percent);
                        var total = parseFloat(faregst) + parseFloat(convenience_fee)+parseFloat(markup);

                        $('#gst').val(result[2]);
                        $('#convenience_fee').val(result[3]);
                        $('#markup').val(result[4]);

                        if(!isNaN(fare))
                        {
                          $('#user_fare'+id).html('');
                          $('#user_fare_details'+id).show();
                          //$('#user_fare'+id).append('Fare:'+data);
                          // $('#base_fare'+id).val(faregst);
                          // $('#total'+id).val(total);
                          $('#gst'+id).val(result[2]);
                          $('#convenience_fee'+id).val(result[3]);
                          $('#markup'+id).val(result[4]);
                          $('#user_fare'+id).text('Fare:'+corencyicon+' '+faregst);
                          $('#user_convenience_fee'+id).text('Convenience_Fees:'+corencyicon+' '+convenience_fee);
                          $('#user_markup_fee'+id).text('Markup:'+corencyicon+' '+parseFloat(result[4])+'.00');
                          $('#user_total'+id).text('Total:'+corencyicon+' '+total.toFixed(2));

                        }
                        else
                        {
                          alert(data);
                          $('#user_fare_details'+id).hide();
                          $('#user_fare'+id).html('');
                          $("#visa_type"+id).val('');
                        }
                          
                   }
                 });
      }

function get_visa_type(visa_type)
{
 if(visa_type=='Other')
 {
   $("#other0").show();
   $("#other_type0").attr("required");
   $("#other_type0").removeAttr("disabled");
   $('#user_fare0').html('');
   $('#user_fare_details0').hide();
 }
 else
 {
   $("#other0").hide();
   $("#other_type0").removeAttr("required");
   $("#other_type0").attr("disabled");
   get_faredetails(0);
 }
}
 function get_duration(duration)
{
 if(duration=='Other')
 {
   $("#others").show();
   $("#no_of_years").attr("required");
   $("#no_of_years").removeAttr("disabled");
 }
 else
 {
   $("#others").hide();
   $("#no_of_years").removeAttr("required");
   $("#no_of_years").attr("disabled");
 }
}

$("input.mydob").change(function(){
dob = new Date($(this).val());
var today = new Date();
var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
$('#age0').val(age);
$('#visa_type0').val('');
$('#user_fare0').html('');
$('#user_fare_details0').show();
});
function changetype(id)
{
  $('#visa_type'+id).val('');
  $('#user_fare'+id).html('');
  $('#user_fare_details'+id).hide();
}
function changetype_visa(id)
{
  $('#visa_type'+id).val('');
  $('#user_fare'+id).html('');
  $('#user_fare_details'+id).hide();
}
function repeat_visa_country()
{
  var cntry = $('#to_country_origin0').val();
  $('.visa_cntry').val(cntry);

}
(function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));
$(".fname_val").inputFilter(function(value) {
  return /^[A-Za-z\s]*$/i.test(value); });

 $('body').delegate(".fname_val1", "keyup", function(value){

    var count_val=$(this).val();
    var regex = /^[A-Za-z\s]*$/;

    if (!regex.test(count_val)) {
      var data = count_val.substr(0, count_val.length - 1);
      // $(this).val(data)
      // alert(a)
        this.value = data;
    }
    else {
        return true;
    }
    
  });
$(".passportnumber").inputFilter(function(value) {
  return /^[A-Za-z0-9]*$/i.test(value); });
$("#mob_no").inputFilter(function(value) {
  return /^[0-9]*$/i.test(value); });

 $('body').delegate(".passportnumber1", "keyup", function(value){

    var count_val=$(this).val();
    var regex = /^[A-Za-z0-9]*$/;

    if (!regex.test(count_val)) {
      var data = count_val.substr(0, count_val.length - 1);
      // $(this).val(data)
      // alert(a)
        this.value = data;
    }
    else {
        return true;
    }
    
  });

$("input.email").change(function(){
var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
var email_val=$(this).val();
if (mailformat.test(email_val))
            return true
        else {
            alert("Please input a valid email address!");
            $('#email1').focus();
            return false;
        }
  
  });
</script>
