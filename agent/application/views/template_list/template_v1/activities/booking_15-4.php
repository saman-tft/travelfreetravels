<?php //debug($package); 
$contrller = '';
if($package->module_type == 'transfers'){

	$contrller = 'transferv1/pre_booking_crs/';
}else{

	$contrller = 'activities/pre_booking/';
}	

?>
<style>
.lprebk > img {
  height: 400px;
  margin: 25px 0;
  width: 100%;
}
.rprebk { background: #fff; margin-top: 25px; margin-bottom: 40px; }
.rprebk h3 {
  margin: 20px 0; color: #006bd7;
}

.rprebk h4 {
  margin: 10px 0; color: #006bd7; font-size: 16px; color: #555; font-weight: 600;
}

.rprebk > p {
  font-size: 15px;
  float: left; width: 100%; overflow: hidden;
}
.butsele .btn.btn-primary {
  float: left;
}
.butsele .form-group > input {
  left: 15px;
}
.imgsele > img {
  width: 100%;
}
.detailsele1 {
  font-size: 14px;
  line-height: 30px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  width: 100%;
}
.credit_item .col-md-4 > p {
  font-size: 13px;
  margin: 0 0 20px;
}


.credit_item p {
  font-size: 13px;
  margin: 10px 0 20px;
}
.newslterinput { margin-bottom: 12px; }
</style>
<div class="prebk clearfix">
	<div class="container">
		<div class="lprebk col-md-8 col-md-8 col-sm-8 col-xs-12">
			<img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>" alt="" />
		</div>
		<div class="rprebk col-md-4 col-md-4 col-sm-4 col-xs-12">
			<h3><?php echo  $package->package_name; ?> </h3>
			<p><?php echo $package->package_description; ?></p>
			<h4>Duration: <?php echo $package->duration; ?> Days </h4>
            <form id="no_persons" action="<?php echo base_url()?>index.php/<?=$contrller?><?php echo  $package->package_id; ?>" method="post">
			<div class="credit clearfix">
			
				<div class="credit_item col-md-12 nopad">
					<div class="col-md-6 nopad">
						<p>No of Adults (12+ YRS)</p>
					</div>
					<div class="col-md-3 nopad">
						<select name="no_adults" class="no_adults holyday_selct1 newslterinput" id="no_adults">
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
							<option>6</option>
							<option>7</option>
							<option>8</option>
							<option>9</option>
							<option>10</option>
						</select>
					</div>
				</div>
				<div class="credit_item col-md-12 nopad">
					<div class="col-md-6 nopad">
						<p>No of Child (5-11 YRS)</p>
					</div>
					<div class="col-md-3 nopad">
						<select name="no_child" class="no_child holyday_selct1 newslterinput" id="no_child">
						<option>0</option>
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
						</select>
					</div>
				</div>

				<div class="credit_item col-md-12 nopad">
					<div class="col-md-6 nopad">
						<p>No of Infant (0-5 YRS)</p>
					</div>
					<div class="col-md-3 nopad">
						<select name="no_infant" class="no_infant holyday_selct1 newslterinput" id="no_infant">
						<option>0</option>
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
						</select>
					</div>
				</div>
			</div>
			
		  	<input type="hidden" name="pack_id" value="<?php echo  $package->package_id; ?>" />
		  	
			<input type="submit" class=" bookcont" id="continue" value="Continue" />
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
//  Check Radio-box
   var adult_count="1";
   var child_count="0";
    $('.no_adults').on('change',function () {
    	adult_count="0";
    	adult_count = parseInt(adult_count, 10) + parseInt(this.value, 10);
       //alert(adult_count);
    });
        $('.no_child').on('change',function () {
    	child_count="0";
        child_count = parseInt(child_count, 10) + parseInt(this.value, 10) ;
       //alert(child_count);
    });
        $('#no_persons').on('submit', function() {
    	var total_persons="0";
    	total_persons= parseInt(adult_count, 10) + parseInt(child_count, 10) ;
    	if(total_persons == "0")
    	{
    		alert("Please select minimum 1 persons");
    		return false;
    	}
    	else if(total_persons>"6")
    	{
    		alert("Please select Maximum of 6 persons");
    		return false;
    	}
    	else
    	{
    		//alert(total_persons);
    		return true;
    	}

    });

});
</script>