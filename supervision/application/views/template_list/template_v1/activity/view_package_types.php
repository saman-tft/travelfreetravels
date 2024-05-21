<style>table.dataTable thead .sorting{background-image:none!important;}
	.widass{width:115px!important;}</style>
<div id="package_types" class="bodyContent col-md-12 yhgjk">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>Excursion Types</h1></a></li>
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
							<div class=''
								style='margin-bottom: 0;'>
								<div class=''>
									<div class='actions'>
										<a
											href="<?php echo base_url(); ?>index.php/activity/add_package_type">
											<button class='btn btn-primary' style='margin-bottom: 5px'>
												<i class='icon-male'></i> + Add Excursion Types
											</button>
										</a> <a class="btn box-collapse btn-xs btn-link" href="#"><i></i></a>
									</div>
								</div>
								<div class=''>
									<div class='responsive-table'>
										<div class='scrollable-area'>
											<table
												class='data-table-column-filter table table-bordered table-condensed table-striped'
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
														<th>Excursion Type</th>
                            <th>Sub Excursion Type</th>
                            <th>Current Status</th>
                            <th>Status Change</th>
													</tr>
												</thead>
												<tbody>
                                      <?php if(!empty($package_view_data)){ $c=1;foreach($package_view_data as $k){
                                        if($k->status==1)
                                        {
                                           $status = '<span style="color:green;">Active</span>';
                                        }
                                        else
                                        {
                                           $status = '<span style="color:red;">In-Active</span>';
                                        }
                                        ?>
                                      <tr>
														<td><?=$c;?></td>
														<td><input type='checkbox' class='interested<?=$j?>'   id='interested_<?=$j?>_<?=$c?>' onclick="uncheck(<?=$j?>);" value="<?=$k->activity_types_id?>" /></td>
														<td>
															 <div class="dropdown2" role="group">
                                               <div class="dropdown slct_tbl pull-left sideicbb">
                                                  <i class="fa fa-ellipsis-v"></i>  
                                                    <ul class="dropdown-menu sidedis" style="display: none;">
                                                  <li> <a class="sideicbb1 sidedis" data-placement="top"
                                                title=""
                                                href="<?php echo base_url(); ?>activity/add_package_type/<?php echo $k->activity_types_id; ?>"
                                                data-original-title="Edit Excursion Type"> <i
                                                class="glyphicon glyphicon-pencil"></i> Edit Excursion Type
                                                </a></li>
                                                <li> <a class="sideicbb2 sidedis" data-placement="top"
                                                title=""
                                                href="<?php echo base_url(); ?>activity/add_sub_category/<?php echo $k->activity_types_id; ?>"
                                                data-original-title="Add Sub Category"> <i
                                                class="glyphicon glyphicon-plus"></i> Add Sub Excursion Type
                                                </a></li>
                                                <li> <a
                                                   href="<?php echo base_url(); ?>index.php/activity/delete_package_type/<?=$k->activity_types_id;?>"
                                                   data-original-title="Delete"
                                                   onclick="return confirm('Are you sure, do you want to delete this record?');"
                                                   class="sideicbb3 sidedis" data-original-title="Delete"> <i
                                                   class="glyphicon glyphicon-trash"></i>Delete Excursion Type
                                                </a></li>
                                              </ul>
                                            </div>
                                          </div>
															
														</td>
														<td><?=$k->activity_types_name;?>
                <td><?php if(in_array($k->activity_types_id,$activity_types_id)){ ?><a href="<?php echo base_url().'index.php/activity/add_sub_category/'.$k->activity_types_id?>"><b>Do you want to add Sub type?</b></a>
                  <?php }else{?><a href="<?php echo base_url().'index.php/activity/get_activity_type/'.$k->activity_types_id?>" data-toggle="modal" data-target="#view_modal" >View Sub Excursion Type</a><?php }?></td>              
                            </td>
														<td><?=$status?></td>
        
          <td>
            <?php if($k->status==1)
            {
              echo '<a class="" data-placement="top" href="'.base_url().'index.php/activity/activation_activity_type/'.$k->activity_types_id.'/0"
              data-original-title="Deactivate Excursion Theme"> <i class="glyphicon glyphicon-th-large"></i></i> De-activate
              </a>';
              }
            else
            { 
          echo '<a class="" data-placement="top" href="'.base_url().'index.php/activity/activation_activity_type/'.$k->activity_types_id.'/1"
              data-original-title="Activate Excursion Theme"> <i class="glyphicon glyphicon-th-large"></i></i> Activate
              </a>';
            } ?>
          </td>
       
													</tr>
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
<div class="modal fade" id="view_modal">
     <div class="modal-dialog">
      <div class="modal-content">

       


    </div>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script>
  $(function () { $('.table').DataTable(); });
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
        alert(checkval);
      if(checkval=='')
      {
        alert('Please Select Any Excursion Type!!')
        return false;
      }
      if(operation=='delete'){
     var result = confirm("Are you sure to delete?");
      if(result){
          
      }else{
        return false;
      }
    }
              var url="<?php echo base_url().'index.php/activity/manage_activity_types'; ?>" ;
              $.ajax({
                      url :url,
                      type: 'POST',
                      data: {checkval:checkval,operation:operation},
                      success: function(data)
                      {
                        location.reload()
                      }
                    });
}
    </script>
