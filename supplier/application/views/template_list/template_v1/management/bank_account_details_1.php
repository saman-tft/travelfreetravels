<?php
if( isset($_GET['eid']) == TRUE OR validation_errors() != FALSE || (isset($_GET['op']) == true && $_GET['op'] == 'add')) {			
	$tab1="active";
	$tab2="";			
} else {
	$tab2="active";
	$tab1="";
}
?>
<div id="bank_details"
	class="bodyContent col-md-12">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
	<li role="presentation" class="<?php echo $tab1;?>"><a href="#fromList"
		aria-controls="home" role="tab" data-toggle="tab"><?php echo get_app_message('AL00323');?>
	<span class="glyphicon glyphicon-pencil"></span></a></li>
	<li role="presentation" class="<?php echo $tab2;?>"><a href="#tabList"
		aria-controls="home" role="tab" data-toggle="tab"><?php echo get_app_message('AL00324');?>
	<span class="glyphicon glyphicon-book"></span></a></li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class=""><!-- PANEL BODY START -->
<div class="tab-content">
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab1;?>" id="fromList">
<div class="">
<?php
/** Generating Form**/
if( isset($_GET['eid']) == false || empty($_GET['eid']) == true ) {
     
     echo $this->current_page->generate_form('bank_account_details',$form_data);
     } else {
     
     echo $this->current_page->generate_form('bank_account_details_edit',$form_data);
     }
?>
</div>
</div>
<!-- Table List -->
<div role="tabpanel" class="tab-pane clearfix <?php echo $tab2;?>" id="tabList">
<div class="col-md-12">
<?php
echo get_table($table_data);
?>
</div>
</div>

</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>

<?php
function get_table($table_data='')
{
	$table = '
<div class="table-responsive col-md-12"><table class="table table-hover table-striped table-bordered table-condensed">';
	$table .= '<tr>
<th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>
<th>'.get_app_message('AL00330').'</th>
<th>'.get_app_message('AL00325').'</th>
<th>'.get_app_message('AL00326').'</th>
<th>'.get_app_message('AL00327').'</th>
<th>'.get_app_message('AL00331').'</th>
<th>'.get_app_message('AL00328').'</th>
<th>'.get_app_message('AL00329').'</th>
<th>'.get_app_message('AL0035').'</th>
<th>'.get_app_message('AL0047').'</th>
<th>'.get_app_message('AL0012').'</th>
</tr>';		

	if (valid_array($table_data) == true) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {			
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td><img height="75px" width="75px" src="'.$GLOBALS ['CI']->template->domain_images('bank_logo/'.$v['bank_icon']).'" alt="Bank Logo"></td>
			<td>'.$v['en_account_name'].'</td>
			<td>'.$v['account_number'].'</td>
			<td>'.$v['en_bank_name'].'</td>
			<td>'.$v['en_branch_name'].'</td>
			<td>'.$v['ifsc_code'].'</td>
			<td>'.$v['pan_number'].'</td>
			<td>'.app_friendly_date($v['created_datetime']).'</td>
			<td>'.get_status_label($v['status']).'</td>
			<td>'.get_edit_button($v['origin']).'</td>			
</tr>';
		}
	} else {
		$table .= '<tr><td colspan="9">'.get_app_message('AL005').'</td></tr>';
	}
	$table .= '</table></div>';
	return $table;
}

function get_edit_button($id)
{
	return '<a role="button" href="'.base_url().'management/bank_account_details?eid='.$id.'" class="btn btn-default btn-sm btn-primary">
		'.get_app_message('AL0041').' <span class="glyphicon glyphicon-pencil"></span></a>
		';
}

function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="label label-success glyphicon glyphicon-hand-right">'.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '<span class="label label-danger glyphicon glyphicon-hand-right">'.get_enum_list('status', INACTIVE).'</span>
		<a role="button" href="" class="hide">'.get_app_message('AL0020').'</a>';
	}
}
?>


<script type="text/javascript">
	$("#bank_account_details input[name=bank_icon]").attr("required","required");
	$("#bank_account_details input[name=bank_icon],#bank_account_details_edit input[name=bank_icon]").parent().parent().find("label").append('<span class="text-danger">*</span><br><span>(1800*400) Upto 970Kb</span>');
	$("#bank_account_details #account_number,#bank_account_details_edit #account_number").attr("type","text").attr("minlength","9").attr("maxlength","18");
	$("#bank_account_details #ifsc_code,#bank_account_details_edit #ifsc_code").attr("maxlength","11");
	$("#bank_account_details #pan_number,#bank_account_details_edit #pan_number").attr("maxlength","10");
	$("#bank_account_details #account_number,#bank_account_details_edit #account_number").on("keypress", function(evt){  
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    });

    $("#bank_account_details input[name=bank_icon]").parent().append('<div id="preview" class="hide1"></div>');
    $("#bank_account_details_edit input[name=bank_icon]").parent().append('<div id="preview" class="hide1"><img src="'+$(".bank_icon_val").val()+'"></div>');
    if($(".bank_icon_val").val()==""){
    	$("#bank_account_details_edit input[name=bank_icon]").attr("required","required");
    }
    //console.log("bank_icon_val="+bank_icon_val);
    // $("#bank_account_details_edit input[name=bank_icon]").parent().append('<div id="preview" class="hide1"></div>');
    window.URL    = window.URL || window.webkitURL;
	var elBrowse  = document.getElementById("bank_icon"),
    elPreview = document.getElementById("preview"),
    useBlob   = false && window.URL; // Set to `true` to use Blob instead of Data-URL
    err_cnt =0;

	// 2.
	function readImage (file) {

	  // Create a new FileReader instance
	  // https://developer.mozilla.org/en/docs/Web/API/FileReader
	  var reader = new FileReader();
	  var err_cnt = 0;

	  // Once a file is successfully readed:
	  reader.addEventListener("load", function () {

	    // At this point `reader.result` contains already the Base64 Data-URL
	    // and we've could immediately show an image using
	    // `elPreview.insertAdjacentHTML("beforeend", "<img src='"+ reader.result +"'>");`
	    // But we want to get that image's width and height px values!
	    // Since the File Object does not hold the size of an image
	    // we need to create a new image and assign it's src, so when
	    // the image is loaded we can calculate it's width and height:
	    var image  = new Image();
	    image.addEventListener("load", function () {
	    	$("#preview").removeClass("hide").html("");
	      // Concatenate our HTML image info 
	      var imageInfo = file.name    +' '+ // get the value of `name` from the `file` Obj
	          image.width  +'×'+ // But get the width from our `image`
	          image.height +' '+
	          file.type    +' '+
	          Math.round(file.size/1024) +'KB';

	        if(image.width>1800){
	        	alert("Image Width is More than 1800px");
	        	$("#preview").addClass("hide");
	        	err_cnt =1;
	        	img_err();
	        }
	        if(image.height>400){
	        	alert("Image height is More than 400px");
	        	$("#preview").addClass("hide");
	        	err_cnt =1;
	        	img_err();
	        }
	        if(file.size>1000000){
	        	alert("File Size is More than 970Kb");
	        	$("#preview").addClass("hide");
	        	err_cnt =1;
	        	img_err();
	        }
	      // Finally append our created image and the HTML info string to our `#preview` 
	      elPreview.appendChild( this );
	      elPreview.innerHTML( this );
	      //elPreview.insertAdjacentHTML("beforeend", imageInfo +'<br>');

	      // If we set the variable `useBlob` to true:
	      // (Data-URLs can end up being really large
	      // `src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAA...........etc`
	      // Blobs are usually faster and the image src will hold a shorter blob name
	      // src="blob:http%3A//example.com/2a303acf-c34c-4d0a-85d4-2136eef7d723"
	      if (useBlob) {
	        // Free some memory for optimal performance

	        window.URL.revokeObjectURL(image.src);

	      }
	    });
			
	    image.src = useBlob ? window.URL.createObjectURL(file) : reader.result;

	  });

	  // https://developer.mozilla.org/en-US/docs/Web/API/FileReader/readAsDataURL
	  reader.readAsDataURL(file);  

	}

	// 1.
	// Once the user selects all the files to upload
	// that will trigger a `change` event on the `#browse` input
	elBrowse.addEventListener("change", function() {

	  // Let's store the FileList Array into a variable:
	  // https://developer.mozilla.org/en-US/docs/Web/API/FileList
	  var files  = this.files;
	  // Let's create an empty `errors` String to collect eventual errors into:
	  var errors = "";

	  if (!files) {
	    errors += "File upload not supported by your browser.";
	  }

	  // Check for `files` (FileList) support and if contains at least one file:
	  if (files && files[0]) {

	    // Iterate over every File object in the FileList array
	    for(var i=0; i<files.length; i++) {

	      // Let's refer to the current File as a `file` variable
	      // https://developer.mozilla.org/en-US/docs/Web/API/File
	      var file = files[i];

	      // Test the `file.name` for a valid image extension:
	      // (pipe `|` delimit more image extensions)
	      // The regex can also be expressed like: /\.(png|jpe?g|gif)$/i
	      if ( (/\.(png|jpeg|jpg|gif)$/i).test(file.name) ) {
	        // SUCCESS! It's an image!
	        // Send our image `file` to our `readImage` function!
	        readImage( file ); 
	      } else {
	        errors += file.name +" Unsupported Image extension\n";  
	      }
	    }
	  }

	  // Notify the user for any errors (i.e: try uploading a .txt file)
	  if (errors) {
	    alert(errors);
	    img_err();
	  }

	});
	function img_err(){
		setTimeout(function(){
			$("#bank_icon").val("");
			$("#preview").html("");
		},1000); 
		
	}
</script>
<style type="text/css">
	#preview img{ height:100px; }
</style>
