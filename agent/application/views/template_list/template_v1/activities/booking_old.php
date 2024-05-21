<?php //debug($package); ?>
<style>
.lprebk > img {
  height: 400px;
  margin: 25px 0;
  width: 100%;
}
.rprebk h3 {
  margin: 20px 0;
}
.rprebk > p {
  font-size: 15px;
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
</style>
<div class="prebk clearfix">
	<div class="container">
		<div class="lprebk col-md-6 col-md-6 col-sm-6 col-xs-12">
			<img src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>" alt="" />
		</div>
		<div class="rprebk col-md-6 col-md-6 col-sm-6 col-xs-12">
			<h3><?php echo  $package->package_name; ?> </h3>
			<p><?php echo $package->package_description; ?></p>
			<h3>Duration: <?php echo $package->duration; ?> Days </h3>
            <form id="no_persons" action="<?php echo base_url()?>index.php/tours/pre_booking/<?php echo  $package->package_id; ?>" method="post">
			<div class="credit clearfix">
			
				<div class="credit_item col-md-12">
					<div class="col-md-4">
						<p>No of Adults (12+ YRS)</p>
					</div>
					<div class="col-md-8">
						<select name="no_adults" class="no_adults" id="no_adults">
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
				<div class="credit_item col-md-12">
					<div class="col-md-4">
						<p>No of Child (2-11 YRS)</p>
					</div>
					<div class="col-md-8">
						<select name="no_child" class="no_child" id="no_child">
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
		  	
			<input type="submit" class="btn btn-primary" id="continue" value="Continue" />
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
    	if(total_persons<"2")
    	{
    		alert("Please select minimum 2 persons");
    		return false;
    	}
    	else if(total_persons>"9")
    	{
    		alert("Please select Maximum of 9 persons");
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