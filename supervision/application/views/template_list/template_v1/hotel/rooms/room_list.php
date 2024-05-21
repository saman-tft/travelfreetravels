<head>



  <script src="http://demo.itsolutionstuff.com/plugin/croppie.js"></script>
  
  <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/croppie.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>hotel_assets/css/custom/theme.css">

</head>
<!--<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
         rel = "stylesheet">
      <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
      <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->

<style>
.center a{
	width: 150px;
    font-size: 13px;
    margin-bottom: 5px;
}


/* Show the tooltip text when you mouse over the tooltip container */

/* Show the tooltip text when you mouse over the tooltip container */

	.addnwhotl {  margin-right: 5px;  margin-top: 5px;}
	.stra_hotel {
	    display: block;
	    margin: 0px 0;
	    overflow: hidden;
	}
	.stra_hotel[data-star="5"] .fa:nth-child(1), .stra_hotel[data-star="5"] .fa:nth-child(2), .stra_hotel[data-star="5"] .fa:nth-child(3), .stra_hotel[data-star="5"] .fa:nth-child(4), .stra_hotel[data-star="5"] .fa:nth-child(5), .stra_hotel[data-star="4"] .fa:nth-child(1), .stra_hotel[data-star="4"] .fa:nth-child(2), .stra_hotel[data-star="4"] .fa:nth-child(3), .stra_hotel[data-star="4"] .fa:nth-child(4), .stra_hotel[data-star="3"] .fa:nth-child(1), .stra_hotel[data-star="3"] .fa:nth-child(2), .stra_hotel[data-star="3"] .fa:nth-child(3), .stra_hotel[data-star="2"] .fa:nth-child(1), .stra_hotel[data-star="2"] .fa:nth-child(2), .stra_hotel[data-star="1"] .fa:nth-child(1) {
    color: #fb9817;
	}
		.pad{padding:3px;}
</style>
<!-- HTML BEGIN -->
<div class="bodyContent">
	<hr>			
            <h4>Advanced Search Panel <button class="btn btn-primary btn-sm toggle-btn" data-toggle="collapse" data-target="#show-search">+
                </button> </h4>
			
            <hr>
            <div id="show-search" class="collapse">
                <form method="GET" autocomplete="off">
                    <div class="clearfix form-group">
                        <div class="col-xs-4">
							<label for="field-1" class="">Hotel Room Type<span class=""></span></label>									
							<div class="">
								 <select id="room_type_id" name="room_type_id" class="form-control" data-validate="required" data-message-required="Please Select the Hotel Room Type">										 
									 <option value="0">Select</option>
									<?php foreach ($room_types_list as $type){ ?>
										<option value="<?php echo $type->id; ?>" data-iconurl=""><?php echo $type->name; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="col-xs-4">
		                  <label for="field-1" class="">Board Type<span class="text-danger">*</span></label>                 
		                  <div class="">
		                     <select id="board_type" name="board_type" class="form-control" data-validate="required" data-message-required="Please Select the Board Type">                    
		                       <option value="0">Select</option>
		                      <?php foreach ($board_types_list as $boardtype){ ?>
		                        <option value="<?php echo $boardtype->id; ?>" data-iconurl=""><?php echo $boardtype->name; ?></option>
		                      <?php } ?>
		                     
		                    </select>
		                  </div>
		                </div>
                    </div>
                    <div class="col-sm-12 well well-sm">
                        <button type="submit" class="btn btn-primary">Search</button> 
                        <!-- <button type="reset" class="btn btn-warning">Reset</button> -->
                        <a href="<?php echo base_url() . 'index.php/hotel/room_crs_list/'.$hotel_id.' ' ?>" id="clear-filter" class="btn btn-primary">Clear Filter</a> 
                    </div>
                </form>
            </div>
	<div class="panel panel-primary clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Room List
			</div>
		</div>
		<a href="<?php echo site_url()."index.php/hotel/hotel_crs_list"; ?>" class="btn btn-primary addnwhotl pull-right">Hotel List</a>
		<a href="<?php echo site_url()."index.php/hotel/add_room_details/{$hotel_id}"; ?>" class="btn btn-primary addnwhotl pull-right">Add Room</a>
		<!-- PANEL HEAD START -->
		<div class="panel-body pull-left" style="width: 100%;">
			<!-- PANEL BODY START -->
			<div class="table-responsive">
				<form action="" method="POST" autocomplete="off">
					<table class="table table-bordered">
						<thead>
						<tr>
							<th>Sl No</th>
							<th>Room Code</th>
							<th>Room Name</th>
							<th>Board Type Name</th>
						
							<th >Maximum Passengers</th>
							<th>Status</th>
							<th>Actions</th>											

						</tr>
						</thead>
				<tbody>
				<?php if(!empty($rooms_list))
							{ 
								foreach($rooms_list as $a => $list)
								{ 
					?>
						<tr>
							<td><?php echo ($a+1); ?></td>
							<td><?php echo 'CRSROOMCODE'.$list->id; ?></td>
							<td><?php echo $list->name; ?></td>
							<td><?php echo $list->boardname; ?></td>
							<td><?php echo $list->max_stay; ?></td>
							<td>
							<?php if($list->status == "ACTIVE")
							{ 
								?>
								<button type="button" class="btn btn-green btn-icon icon-left my-actve">Active<i class="entypo-check"></i></button>
							<?php 
							}
							else
							{ 
								?>
									<button type="button" class="btn btn-orange btn-icon icon-left my-inactve">InActive<i class="entypo-cancel"></i></button>
							<?php 
							} 
							?>
							</td>
							<td class="center">
								<?php   
								if($list->status == "ACTIVE")
									{ 
								?>
									<a href="<?php echo site_url()."index.php/hotel/inactive_room/".$list->hotel_id.'/'.$list->id; ?>" class="pad" title="Inactivate"><i class="fa fa-times" aria-hidden="true"></i></a>
								<?php 
								}
								else
								{ ?>
									<a href="<?php echo site_url()."index.php/hotel/active_room/".$list->hotel_id.'/'.$list->id; ?>" class="pad" title="Activate"><i class="fa fa-check" aria-hidden="true"></i></a>
								<?php 
								} 
								?>
							<a href="<?php echo site_url()."index.php/hotel/edit_room/".$list->hotel_id.'/'.$list->id; ?>" class=" pad" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>	<br>


							<?php
							if($seasons_list > 0)
							{
							?>
								<a href="<?php echo site_url()."index.php/hotel/room_price_list/".$list->hotel_id.'/'.$list->id; ?>" class=" pad" title="Price Info List"><i class="fa fa-database" aria-hidden="true"></i></a>
								<?php } else { ?>
									<a id="season_add" class=" pad" title="Price Info List"><i class="fa fa-money" aria-hidden="true"></i></a>
								<?php } ?>


								<a href="<?php echo site_url()."index.php/hotel/room_cancellation_list/".$list->hotel_id.'/'.$list->id; ?>" class=" pad" title="Cancellation Policy List"><i class="fa fa-handshake" aria-hidden="true"></i></a>
							</td>
						</tr>
					<?php 
					}
					}else{ ?>
						<tr>
							<?php echo'No Records Found'; ?>
						</tr>
					<?php } 
					?>													
					</tbody>
				</table>
				</form>
			</div>
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL END -->
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

    var base_imgurl='<?php echo IMG_BASEURL;?>supervision/uploads/hotel_images/';
    console.log(base_imgurl);
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
			url: "<?php echo base_url();?>general/uplodhtl_croppimg",
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


<!-- Page Ends Here -->
<script type="text/javascript">

	function checkAll(ele) {
	     var checkboxes = document.getElementsByTagName('input');
	     if (ele.checked) {
	         for (var i = 0; i < checkboxes.length; i++) {
	             if (checkboxes[i].type == 'checkbox') {
	                 checkboxes[i].checked = true;
	             }
	         }
	     } else {
	         for (var i = 0; i < checkboxes.length; i++) {
	             console.log(i)
	             if (checkboxes[i].type == 'checkbox') {
	                 checkboxes[i].checked = false;
	             }
	         }
	     }
	 }

	 function activate_hotels(ele){
	 	var favorite = [];
            $.each($("input[name='select_hotel_id']:checked"), function(){            
                favorite.push($(this).val());
            });
            if(favorite != '' && favorite != null){
	            $.ajax({
					type: "POST",
					url: "<?= site_url()."/hotels/activate_multiple_hotels" ?>",
					data: 'data='+favorite,
					dataType: "json",
					success: function(data){
						if(data.status == 1){
							window.location.href = data.success_url;
						}
					}
				});
			}else{
				alert("Please select atleast one checkbox");
			}
	 }
	 function inactivate_hotels(ele){
	 	var favorite = [];
            $.each($("input[name='select_hotel_id']:checked"), function(){            
                favorite.push($(this).val());
            });
            if(favorite != '' && favorite != null){
	            $.ajax({
					type: "POST",
					url: "<?= site_url()."/hotels/inactivate_multiple_hotels" ?>",
					data: 'data='+favorite,
					dataType: "json",
					success: function(data){
						if(data.status == 1){
							window.location.href = data.success_url;
						}
					}
				});
			}
	 }
</script>

<style>
.align_line button {
    padding: 0px 2px;
    line-height: normal;
    font-size: 12px;
    position: absolute;
    margin-right: 13px;
    margin-top: 3px;
}
.align_line input {
    margin-left: -6px;
    margin-right: 2px;
}




</style>
<script type="text/javascript">
	function myFunctiondel(argument) {
		//alert(argument);
	    if (confirm("Do You want to delete ?")) {
	        location.href = argument;
	    }
	}




	$(document).on("click", "#season_add", function () {
	
	
    window.alert('Please add season before adding price details.');
    window.location.href='<?=base_url("hotels/season_list/{$this->uri->segment(3)}")?>';
  
});
</script>