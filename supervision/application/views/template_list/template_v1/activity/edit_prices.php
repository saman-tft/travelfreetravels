<?php
//debug($currency);exit;?>
<head>



  <script src="http://demo.itsolutionstuff.com/plugin/croppie.js"></script>
  
  <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/croppie.css">
</head>

<div id="package_types" class="bodyContent col-md-12 yhgjk">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab">
							<h1>Add Nationality Price</h1>
					</a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="fromList">
					<div class="col-md-12 bxpd">
						<div class='row'>
							<div class='col-sm-12 bxpd'>
									<div class='addprc' style='margin-bottom: 30px;'>
										<div class='box-header blue-background '>
											<div class='actions'>
												<form action='<?php echo base_url(); ?>index.php/activity/add_price_new' method="post" 
													class='form form-horizontal validate-form'>
													<input type="hidden" id="pckge_id" name="pckge_id"
														value="<?php echo $package_id;?>">
								
								<div class='form-group col-sm-4'>
								<label class='control-label col-sm-3' for='validation_country'>Nationality</label>
								<div class='col-sm-9 controls'>

									 
									<select class='select2 form-control price_element'
										data-rule-required='true' name='nationality' id="nationality" onchange="get_currency(this.value);" required>
										
										<option value="0">Select Currency</option>
				                        <?php foreach ($currency as $coun) {
				                        	?>
				                        <option value="<?php echo $coun->country_list; ?>"><?php echo $coun->country_name; ?></option>
				                        <?php }?>
				                      </select>
								</div>
							</div>

							<div class='form-group col-sm-4'>
								<label class='control-label col-sm-3' for='adult'>Currency</label>
								<div class='col-sm-9'>
									<input type="text" name="currency" id="currency"
										data-rule-required='true'
										
										class='form-control' readonly="readonly"  required>
								</div>
							</div>

								


							<div class='form-group col-sm-4'>
								<label class='control-label col-sm-3' for='adult'>Adult Price</label>
								<div class='col-sm-9'>
									<input type="text" name="p_price" id="p_price"
										data-rule-number="true" data-rule-required='true'
										placeholder="Adult Price"
										class='form-control price_element numeric' maxlength='10'
										minlength='' required>
								</div>
							</div>
							<div class='form-group col-sm-4'>
								<label class='control-label col-sm-3' for='adult'>Child Group 1 Price</label>
								<div class='col-sm-9'>
									<input type="text" name="child_price" id="child_price"
										data-rule-number="true" data-rule-required='true'
										placeholder="Child Group 1 Price"
										class='form-control price_element numeric' maxlength='10'
										minlength='' required>
								</div>
							</div>
							<div class='form-group col-sm-4'>
								<label class='control-label col-sm-3' for='adult'>Child Group 2 Price</label>
								<div class='col-sm-9'>
									<input type="text" name="child_price_grp2" id="child_price_grp2"
										data-rule-number="true" data-rule-required='true'
										placeholder="Child Group 2 Price"
										class='form-control price_element numeric' maxlength='10'
										minlength='' required>
								</div>
							</div>
						
							</div>
								<!-- <div class="addDiscountBox"><span class="btn btn-info pull-right addDiscountBtn">Add</span></div>
							</div> -->
													
												
													<div class='form-actions addpr' style='margin-bottom: 10px;margin-top: 10px'>
														<div class='row'>
															<div class='col-sm-9 col-sm-offset-4'>
																<?php 
																if($type=='admin')
														          {
														            $view_name = 'view_with_price';
														          }
														          if($type=='staff')
														          {
														            $view_name = 'view_with_price_staff';
														          }
														          if($type=='supplier')
														          {
														            $view_name = 'view_with_price_supplier';
														          }
																?>
																<button class='btn btn-primary' type='submit'>Add Price</button>&nbsp;&nbsp;
																<a href="<?php echo base_url(); ?>activity/<?=$view_name?>" class="btn btn-primary">Go Back</a>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
										<div class='box-content box-no-padding'>
											<div class='responsive-table'>
												<div class='scrollable-area'>
													<table
														class='data-table-column-filter table table-bordered table-striped'
														style='margin-bottom: 0;'>
														<thead>
															<tr>
																<th>S.no</th>
																<th>Action</th>
																<th>Nationality</th>
																<th>Currency</th>
																<th>Adult Price</th>
																<th>Child Group 1 Price</th>
																<th>Child Group 2 Price</th>
																<th>Final Price Adult(AED)</th>
																<th>Final Price Child(AED)</th>
															</tr>
														</thead>
														<tbody>
													<?php
													
													if (! empty ( $price_data )) {
														$count = 1;
														foreach ( $price_data as $key => $price ) {
															foreach ($currency as $coun) {
																$countryID = $coun->country_list;
																if($countryID == $price->country_id)
																{
																$countryName = $coun->country_name;
																break;
																}
															}

															?>
											<tr>
																<td><?php echo $count; ?></td>

																<td class="center">

															<div class="dropdown2" role="group">
															<div class="dropdown slct_tbl pull-left sideicbb"> <i class="fa fa-ellipsis-v"></i>
															    <ul class="dropdown-menu sidedis" style="display: none;">
															        <li> <a
																	href="<?php echo base_url() ?>activity/delete_price/<?php echo $price->id; ?>/<?php echo $price->package_id; ?>"
																	data-original-title="Delete"
																	onclick="return confirm('Do you want delete this record');"
																	class="sideicbb3 sidedis"
																	data-original-title="Delete"> <i class="fa fa-trash"></i>
																		Delete
																</a></li>
															    </ul>
															</div>
															</div>
																	</td>
																<td><?php echo $countryName; ?></td>
																<td><?php echo $price->currency_code; ?></td>
																<td><?php echo $price->price; ?></td>
																<td><?php echo $price->child_price; ?></td>
																<td><?php echo $price->child_price_group2; ?></td>
																<td><?php echo round($price->final_price,2);?></td>
																<td><?php echo round($price->final_price_child,2);?></td>

															</tr>		
									<?php $count++; } } ?>	
											</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								
							</div>
						</div>
					</div>

					</form>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>
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


<style>
/*
.cr-image
{
	width:100%;
	height:100%;
}
.cr-slider-wrap
{
	display:none;
}
</style>

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
    var base_imgurl='<?php echo IMG_BASEURL;?>extras/custom/keWD7SNXhVwQmNRymfGN/uploads/packages/';
   //var base_imgurl='<?php echo IMG_BASEURL;?>extras/custom/TMX1512291534825461/uploads/activity/';
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
			url: "<?php echo base_url();?>general/uplod_croppimg",
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

<script type="text/javascript">
    function activate(that) { window.location.href = that; }


 	function get_currency(country_id)
		{
			var pckge_id = $('#pckge_id').val();
		 $.ajax({
		        url: app_base_url+'activity/get_currency_detls',
		        type: 'POST',
		        data: {country_id:country_id,pckge_id:pckge_id},
		        success:function(result){
		          if(result==0){
		          	alert('Price Already Map for this Country');
		          	$('#nationality').val(0);
		          	$('#currency').val('');
		          	return false;
		          }else{
		            $('#currency').val(result);
		          }
		        }
		      });
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
$(".price_element").inputFilter(function(value) {
  return /^[0-9.]*$/i.test(value); });
    </script>