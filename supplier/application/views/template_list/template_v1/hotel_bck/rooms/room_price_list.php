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
</style>
<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel panel-primary clearfix">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<i class="fa fa-credit-card"></i> Room Price List
			</div>
		</div>
		<a href="<?php echo site_url()."/hotels/room_crs_list/{$hotel_id}"; ?>" class="btn btn-primary addnwhotl pull-right">Back</a>
		<a href="<?php echo site_url()."/hotels/add_room_price/{$hotel_id}/{$room_id}"; ?>" class="btn btn-primary addnwhotl pull-right">Add Room Price</a>
		<!-- PANEL HEAD START -->
		<div class="panel-body pull-left">
			<!-- PANEL BODY START -->
			<div class="table-responsive">
				<form action="" method="POST" autocomplete="off">
					<table class="table table-striped">
						<tr>
							<th>Sl No</th>
							<th>Room Code</th>
							<th>Date From</th>
							<th>Date To</th>
							<th>Adult 1 Price</th>
							<th>Adult 2 Price</th>
							<th>Adult 3 Price</th>
							<th>Adult 4 Price</th>
							<th>Child 1 Price</th>
							<th>Child 2 Price</th>
					
							<th>Status</th>
							<th>Actions</th>											
							<th></th>											
						</tr>
				<tbody>
				<?php if(!empty($price_list))
							{ 
								foreach($price_list as $a => $list)
								{ 
					?>
						<tr>
							<td><?php echo ($a+1); ?></td>
							<td><?php echo 'CRSROOMCODE'.$list->room_id; ?></td>
							<td><?php echo $list->date_from; ?></td>
							<td><?php echo $list->date_to; ?></td>
							<td><?php echo $list->one_adult; ?></td>
							<td><?php echo $list->two_adult; ?></td>
							<td><?php echo $list->three_adult; ?></td>
							<td><?php echo $list->four_adult; ?></td>
							<td><?php echo $list->one_child; ?></td>
							<td><?php echo $list->two_child; ?></td>
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
									<a href="<?php echo site_url()."/hotels/inactive_room_price/".$list->hotel_id.'/'.$list->id; ?>" class="btn btn-orange btn-sm btn-icon icon-left my-inactve"><i class="entypo-eye"></i>InActive</a>
								<?php 
								}
								else
								{ ?>
									<a href="<?php echo site_url()."/hotels/active_room_price/".$list->hotel_id.'/'.$list->id; ?>" class="btn btn-green btn-sm btn-icon icon-left my-actve"><i class="entypo-check"></i>Active</a>
								<?php 
								} 
								?>
								
							</td>
							
							<td>
							    <a href="<?php echo site_url()."/hotels/edit_room_price/".$list->id; ?>" class="btn btn-primary btn-sm btn-icon icon-left my-actve"><i class="entypo-edit"></i>Edit</a>
							</td>
						</tr>
					<?php 
					}
					} 
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
</script>