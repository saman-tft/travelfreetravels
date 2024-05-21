<head>



  <script src="http://demo.itsolutionstuff.com/plugin/croppie.js"></script>
  
  <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/croppie.css">
</head>

<div id="package_types" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab">
							<h1>Add Traveller Photos</h1>
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
					<div class="col-md-12">
						<div class='row'>
							<div class='container'>
								<div class='col-sm-10'>
									<div class='' style='margin-bottom: 0;'>
										<div class='box-header blue-background '>
											<div class='actions'>
												<form action='' method="post" enctype="multipart/form-data"
													class='form form-horizontal validate-form'>
													<input type="hidden" name="pckge_id"
														value="<?php echo $package_id;?>">
													<div class='form-group'>
														<label class='control-label col-sm-3'
															for='validation_company'>Add Traveller Display Image</label>
														<div class='col-sm-4 controls'>
															<input type="file" title='Image to add'
																class='add_pckg_elements' data-rule-required='true'
																id='photo' name='traveller' required> <span id="pacmimg"
																style="color: #F00; display: none">Please Upload Package
																Image</span>
														</div>
													</div>
													<div class='form-actions' style='margin-bottom: 0'>
														<div class='row'>
															<div class='col-sm-9 col-sm-offset-3'>
																<button class='btn btn-primary' type='submit'>Add image</button>&nbsp;&nbsp;
																<a href="<?php echo base_url(); ?>index.php/transfers/view_with_price" class="btn btn-primary">Go Back</a>
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
																<th>Traveller Photos</th>
																<th>Status</th>
																<th>Action</th>
															</tr>
														</thead>
														<tbody>
													<?php
													
													if (! empty ( $traveller )) {
														$count = 1;
														foreach ( $traveller as $key => $travel ) {
															?>
											<tr>
																<td><?php echo $count; ?></td>
																<td><a data-id="<?php echo $travel->traveller_image; ?>" data-toggle="modal" class='openimg' href="#openModalx">  <img
																		width="50" title="" alt=""
																		src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($travel->traveller_image); ?>"></a></td>
																<td>
                                      <?php if ($travel->status == '1') { ?>
                                              <img width="25"
																	height="25"
																	src="<?php echo DOMAIN_IMAGE_DIR; ?>active.jpg">
                                      <?php } else { ?>
                                              <img width="25"
																	height="25"
																	src="<?php echo DOMAIN_IMAGE_DIR; ?>inactive.jpg">
                                              <?php } ?>
                                              <?php if ($travel->status == '1') { ?>
                                                  Activated
                                      <?php } else { ?>
                                          <select
																	onchange="activate(this.value);">
																		<option
																			value="<?php echo base_url() ?>transfers/update_traveller_image_status/<?php echo $travel->package_id; ?>/<?php echo $travel->img_id; ?>/1">Activate</option>
																		<option
																			value="<?php echo base_url() ?>supplier/update_traveller_image_status/<?php echo $travel->package_id; ?>/<?php echo $travel->img_id; ?>/0"
																			selected>De-activate</option>
																</select>
                                      <?php } ?>
                        </td>
																<td class="center"><a
																	href="<?php echo base_url() ?>index.php/transfers/delete_traveller_img/<?php echo $travel->img_id; ?>/<?php echo $travel->package_id; ?>"
																	data-original-title="Delete"
																	onclick="return confirm('Do you want delete this record');"
																	class="btn btn-danger btn-xs has-tooltip"
																	data-original-title="Delete"> <i class="icon-remove"></i>
																		Delete
																</a></td>

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
    </script>