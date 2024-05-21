<div id="package_types" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>Transfers
								Types</h1></a></li>
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
							<div class='' style='margin-bottom: 0;'>
								<div class=''>
									<div class='actions'>
										<a
											href="<?php echo base_url(); ?>index.php/transfers/add_package_type">
											<button class='btn btn-primary' style='margin-bottom: 5px'>
												<i class='icon-male'></i> + Add Transfers Types
											</button>
										</a> <a class="btn box-collapse btn-xs btn-link" href="#"><i></i></a>
									</div>
								</div>
								<div class=''>
									<div class='responsive-table'>
										<div class='scrollable-area'>
											<table
												class='data-table-column-filter table table-bordered table-striped'
												style='margin-bottom: 0;'>
												<thead>
													<tr>
														<th>S.No</th>
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
														<th>Actions</th>
														<th>Transfer Type</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
                                      <?php if(!empty($package_view_data)){ $c=1;foreach($package_view_data as $k){?>
                                      <tr>
														<td><?=$c;?></td>
														<td><input type='checkbox' class='interested<?=$j?>'   id='interested_<?=$j?>_<?=$c?>' onclick="uncheck(<?=$j?>);" value="<?=$k->package_types_id?>" /></td>
														<td>
															<div class="dropdown2" role="group">
				   <div class="dropdown slct_tbl pull-left sideicbb">
					   <i class="fa fa-ellipsis-v"></i>  
					    <ul class="dropdown-menu sidedis" style="display: none;">
																<li><a class="sidedis sideicbb1"
																	data-placement="top" title=""
																	href="<?php echo base_url(); ?>index.php/transfers/add_package_type/<?php echo $k->package_types_id; ?>"
																	data-original-title="Edit Transfer Type"> <i class="fa fa-edit"></i>Edit
																</a> </li>
																<li><a class='sidedis sideicbb3'
																	data-placement='top' title='Delete'
																	onclick="return confirm('Are you sure, do you want to delete this record?');"
																	href='<?php echo base_url(); ?>index.php/transfers/delete_package_type/<?=$k->package_types_id;?>'>
																	<i class='fa fa-trash'></i>Delete
																</a>
															</li>
														</ul>
													</div>
															</div>
														</td>
														<td><?=$k->package_types_name;?></td>
														<td><?php if ($k->status == 1) { ?>
                                  <span style="color:green;">Active</span>
                                  <?php } else { ?>
                                  <span style="color:red;">In-Active</span>
                                <?php } ?>
                                             </td>
                                      <?php $c++;}}?>
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
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
</div>
<script>
function activate(that) { window.location.href = that; }
$.validator.addMethod("buga", (function(value) {
  return value === "buga";
}), "Please enter \"buga\"!");

$.validator.methods.equal = function(value, element, param) {
  return value === param;
};

$(function () {
  $('#datetimepicker2').datetimepicker({
      startDate: new Date()
  });

  $('#datetimepicker1').datetimepicker({
      startDate: new Date()
  });
});
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
      var theme_tbl = 'package_types';
      var redrct_page = 'view_package_types';
      var id = 'package_types_id';
      if(checkval=='')
      {
        alert('Please Select Any Transfer Type!!')
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
                      data: {checkval:checkval,operation:operation,theme_tbl:theme_tbl,redrct_page:redrct_page,id:id},
                      success: function(data)
                      {
                        location.reload()
                      }
                    });
}

    </script>
