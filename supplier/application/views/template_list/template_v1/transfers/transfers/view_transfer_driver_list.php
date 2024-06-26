<!-- HTML BEGIN -->
 
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
							<h1>View Transfers Driver</h1>
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
							<div class='col-sm-12'>
								<div class='' style='margin-bottom: 0;'>
									<div class=' '>
										<div class='actions'>
											<a href="<?php echo base_url(); ?>index.php/transfers/transfer_driver">
												<button class='btn btn-primary' style='margin-bottom: 5px'>
													+ Add Driver</button>
											</a> <a href="#"><i> &nbsp</i></a>
										</div>
									</div>
                  <?php if(isset($status)){echo $status;}?>
                  <div class='responsive-table'>
										<div class=''>
											<div class='scrollable-area'>
												<table class=' table table-bordered'
													style='margin-bottom: 0;'>
													<thead>
														<tr>
															<th>S.no</th>
															<?php $j=1; ?>
                                             <th class="widass"><input type='checkbox' name='alll' id='selectall<?=$j?>' onclick='checkall(<?=$j?>);'>&nbsp;&nbsp;&nbsp;<b>Select All </b>
        <div class="dropdown2" role="group" style="float:right">
                                <div class="dropdown slct_tbl pull-left hjkuu"> <i class="fa fa-ellipsis-v"></i>
                                    <ul class="dropdown-menu sidedis" style="display: none;">
                                        <li> <a href="#" class="sideicbb3 sidedis" onclick="manage_details(<?=$j?>,'deactivate');"><i class="fa fa-toggle-off" ></i>Deactivate</a> </li>
                                        <li> <a href="#" class="sideicbb3 sidedis" onclick="manage_details(<?=$j?>,'activate');"><i class="fa fa-toggle-on" ></i>Activate</a> </li>
                                        <li> <a href="#" class="sideicbb3 sidedis" onclick="manage_details(<?=$j?>,'delete');"><i class="fa fa-trash" ></i>Delete</a> </li>
                                    </ul>
                                </div>
                            </div></th>
															<th>Action</th>
															<th>Driver Name</th>
															<th>Contact</th>
															<th>Email</th>
															<th>Country</th>
															<th>City</th>
															<th>Shift Days</th>
															<th>Shift Time From</th>
															<th>Shift Time To</th>
															<th>Driver Photo</th>
															<th>Status</th>
														</tr>
													</thead>
													<tbody>
                            <?php
									if (! empty ( $data_list )) {
									$count = 1;
									// debug($data_list);exit;	
									$path = $GLOBALS['CI']->template->domain_upload_pckg_images();			
									// debug($path);	
									foreach ( $data_list as $key => $data ) {
										// debug($data);exit;
										$driver_shift_from = date("h:i A", mktime(0,$data->driver_shift_from));
                                  				 
                                  		$driver_shift_to = date("h:i A", mktime(0,$data->driver_shift_to));
							?>
                             <tr>
								<td><?php echo $count; ?></td>
								<td><input type='checkbox' class='interested<?=$j?>'   id='interested_<?=$j?>_<?=$count?>' onclick="uncheck(<?=$j?>);" value="<?=$data->id?>" /></td>
								<td class="center">
												<div class="dropdown2" role="group">
				   <div class="dropdown slct_tbl pull-left sideicbb">
					   <i class="fa fa-ellipsis-v"></i>  
					    <ul class="dropdown-menu sidedis" style="display: none;left: 30px;">
												<li><a class="sidedis sideicbb1" data-placement="top"
												title=""
												href="<?php echo base_url(); ?>index.php/transfers/transfer_driver/<?php echo $data->id; ?>"
												data-original-title="Edit Transfer"> <i
													class="glyphicon glyphicon-pencil"></i> Edit Driver Details
											</a></li>
											<li><a class="sidedis sideicbb3" href="<?php echo base_url(); ?>transfers/delete_transfer_driver/<?php echo $data->id; ?>"
												data-original-title="Delete"
												onclick="return confirm('Do you want delete this record');"
												class="" data-original-title="Delete"> <i
													class="glyphicon glyphicon-trash"></i>Delete Driver
											</a></li>
										</ul>
									</div>
								</div>
										</td>
								<td><?php echo $data->driver_name; ?></td>
								<td><?php echo $data->contact_number; ?></td>
								<td><?php echo $data->email; ?></td>
								<td><?php echo $data->country_name; ?></td>
								<td><?php echo $data->city; ?></td>
								<td><a href="<?php echo base_url().'index.php/transfers/get_driver_shift_days/'.$data->id?>" data-toggle="modal" data-target="#view_modal" >View Driver Shift Days</a></td>
								<td><?php echo $driver_shift_from; ?></td> 
								<td><?php echo $driver_shift_to; ?></td> 
								
					            <td>  
				             	<img width="70"	height="60" title="<?=  $data->driver_name; ?>" alt="<?=  $data->driver_name; ?>"
								src="<?php echo $path.$data->driver_photo ?>"></td>

								<td>
                                  <?php if ($data->status == 'ACTIVE') { ?>
                                  <span style="color:green;">Active</span>
                                  <?php } else { ?>
                                  <span style="color:red;">In-Active</span>
                                <?php } ?>
                                 </td>

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
	<!-- PANEL BODY END -->
</div>
<!-- PANEL WRAP END -->
</div>








<div class="modal fade" id="view_modal">
     <div class="modal-dialog">
      <div class="modal-content">

       


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

</script>
<script type="text/javascript">
  function activate(that) { window.location.href = that; }

  function uncheck(id){
$('#selectall'+id).prop('checked', false);
   }
function checkall(id){ 
if($('#selectall'+id).is(':checked')) { 

 $('.interested'+id).prop('checked', true); 
 
} 
else{ 
 $('.interested'+id).prop('checked', false); 
} 
  // for unselect disabled checkbox
   $('.interested'+id+':checked').map( 
  
    function(){ 
      var idd=$(this).attr('id');
      
      if($('#'+idd).is(':disabled')) {
      
      $('#'+idd).prop('checked', false); 
    } 
    }).get(); 

}
function manage_details(id,operation)
{
  
      var checkval = $('.interested'+id+':checked').map( function(){ return $(this).val();}).get(); 
      var theme_tbl = 'transfer_driver_info';
      var id = 'id';
      if(checkval=='')
      {
        alert('Please Select Any Driver Details!!')
        return false;
      }
      if(operation=='delete'){
     var result = confirm("Are you sure to delete?");
      if(result){
          
      }else{
        return false;
      }
    }
              var url="<?php echo base_url().'index.php/transfers/manage_transfers_all_details'; ?>" ;
              $.ajax({
                      url :url,
                      type: 'POST',
                      data: {checkval:checkval,operation:operation,theme_tbl:theme_tbl,id:id},
                      success: function(data)
                      {
                        location.reload()
                      }
                    });
}

</script>
<style>
.external>tbody>tr>td, .external>tbody>tr>th, .external>tfoot>tr>td,
	.external>tfoot>tr>th, .external>thead>tr>td, .external>thead>tr>th {
	padding: 6px;
}
</style>