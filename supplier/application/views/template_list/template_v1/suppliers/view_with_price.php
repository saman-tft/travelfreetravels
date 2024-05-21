<!-- HTML BEGIN -->

<head>



  <script src="http://demo.itsolutionstuff.com/plugin/croppie.js"></script>
  
  <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/croppie.css">
</head>
<div id="package_types" class="bodyContent col-md-12">
	<div class="panel panel-default" style="width: 100%;float: left;">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab">
							<h1>View Activities</h1>
					</a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="col-md-12">
		<div class="panel-body" style="padding: 15px 0px;">
			<!-- PANEL BODY START -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="fromList">
					<div class="col-md-12" style="padding:0px;">
						<div class='row'>
							<div class='col-sm-12' style="padding:0px;">
								<div class='' style='margin-bottom: 0;'>
									<div class=' '>
										<div class='actions'>
											<a href="<?php echo base_url(); ?>index.php/supplier/add_with_price">
												<button class='btn btn-primary' style='margin-bottom: 5px'>
													+ Add Activity</button>
											</a> <a href="#"><i> &nbsp</i></a>
										</div>
									</div>
                  <?php if(isset($status)){echo $status;}?>
                  <div class='responsive-table'>
										<div class='col-md-12' style="padding: 0px;">
											<div class='scrollable-area'>
												<table class=' table-striped external table table-bordered'
													style='margin-bottom: 0;'>
													<thead>
														<tr>
															<th>S.no</th>
															<th>Activity Name</th>
															<th>Location</th>
															<th>Image</th>
															<th>Status</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
                            <?php
																												if (! empty ( $newpackage )) {
																													$count = 1;
																													foreach ( $newpackage as $key => $package ) {
																														?>
                            <tr>
															<td><?php echo $count; ?></td>
															<td><?php echo $package->package_name; ?></td>
															<td><?php echo $package->package_city; ?>,<?php $country = $this->Supplierpackage_Model->get_country_name($package->package_country); echo $country->name; ?></td>
		
				
				
				
					<td><a data-id="<?php echo $package->image; ?>" data-toggle="modal" class='openimg' href="">  <!-- href="#openModal"   -->
					<img width="70"	height="60" title="<?= $package->package_name; ?>" alt="<?= $package->package_name; ?>"
								src="<?php echo $GLOBALS['CI']->template->domain_upload_pckg_images($package->image); ?>"></a></td>
															<td>   <?php 
                                   if($package->status =="1")
                                   {
                                   		echo '<span style="color:green;">Completed</span>';
                                   }else
                                   {
                                   		echo '<span style="color:red;">In-Complete</span>';
                                   }	

                                   ?>
                              </td>
															<td class="center"><a class="" data-placement="top"
																title=""
																href="<?php echo base_url(); ?>index.php/supplier/edit_with_price/<?php echo $package->package_id; ?>"
																data-original-title="Edit Package"> <i
																	class="glyphicon glyphicon-pencil"></i> Edit Activity
															</a>&nbsp; <a class="" data-placement="top" title=""
																href="<?php echo base_url(); ?>index.php/supplier/edit_itinerary/<?php echo $package->package_id; ?>"
																data-original-title="Edit Itinerary"> <i
																	class="glyphicon glyphicon-pencil "></i> Edit Itinerary
															</a><br> <a class='' data-placement='top'
																title='Change Images'
																href='<?php echo base_url(); ?>index.php/supplier/images/<?=$package->package_id;?>/w'>
																	<i class='glyphicon glyphicon-th-large'></i> Edit
																	Images
															</a>&nbsp; <br> <a
																href="<?php echo base_url(); ?>supplier/delete_package/<?php echo $package->package_id; ?>"
																data-original-title="Delete"
																onclick="return confirm('Do you want delete this record');"
																class="" data-original-title="Delete"> <i
																	class="glyphicon glyphicon-trash"></i>Delete Activity
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
<style>
.external>tbody>tr>td, .external>tbody>tr>th, .external>tfoot>tr>td,
	.external>tfoot>tr>th, .external>thead>tr>td, .external>thead>tr>th {
	padding: 6px;
}
</style>