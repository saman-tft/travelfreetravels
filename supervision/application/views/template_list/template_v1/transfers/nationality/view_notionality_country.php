
<div id="package_types" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>Nationality Country</h1></a></li>
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
							<div class=''
								style='margin-bottom: 0;'>
								<div class=''>
									<div class='actions'>
										<a
											href="<?php echo base_url(); ?>index.php/transfers/nationality_country">
											<button class='btn btn-primary' style='margin-bottom: 5px'>
												<i class='icon-male'></i> + Add Nationality Country
											</button>
										</a> <a class="btn box-collapse btn-xs btn-link" href="#"><i></i></a>
									</div>
								</div>
								<div class=''>
									<div class='responsive-table table-responsive'>
										<div class='scrollable-area'>
											<table
												class='data-table-column-filter table table-bordered table-striped'
												style='margin-bottom: 0;'>
												<thead>
													<tr>
														<th>S.No</th>
														<?php $j=1; ?>
					<th><input type='checkbox' name='alll' id='selectall<?=$j?>' onclick='checkall(<?=$j?>);'>&nbsp;&nbsp;&nbsp;<b>Select All </b>
						<div class="dropdown2" role="group">
				            <div class="dropdown slct_tbl pull-left sideicbb"> <i class="fa fa-ellipsis-v"></i>
				                <ul class="dropdown-menu sidedis" style="display: none;">
				                    <li> <a href="#" class="sideicbb sidedis" onclick="manage_details(<?=$j?>,'deactivate');"><i class="fa fa-toggle-off" ></i>Deactivate</a> </li>
				                    <li> <a href="#" class="sideicbb sidedis" onclick="manage_details(<?=$j?>,'activate');"><i class="fa fa-toggle-on" ></i>Activate</a> </li>
				                    <li> <a href="#" class="sideicbb sidedis" onclick="manage_details(<?=$j?>,'delete');"><i class="fa fa-trash" ></i>Delete</a> </li>
				                </ul>
				            </div>
				        </div></th>
														<th>Actions</th>
														<th>Contitent</th> 
														<th>Nationality Type</th>
														<th>Included Countries</th>
														<th>Except Countries</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
                                      <?php 
                                       
                                       if(!empty($notionality_country))
                                       	{ 
                                       		$c=1;
                                       		foreach($notionality_country as $k)
                                       		{
                                       			// debug($k);
                                       			if($k['status']==1)
										        {
										           $status = '<span style="color:green;">Active</span>';
										        }
										        else
										        {
										           $status = '<span style="color:red;">In-Active</span>';
										        }
                                       			echo '
                                       				<tr>
                                       					<td>'.$c.'</td>
                                       					<td><input type="checkbox" class="interested'.$j.'"  id="interested_'.$j.'_'.$c.'" onclick="uncheck('.$j.');" value="'.$k['origin'].'" /></td>
                                       					<td class="center">
													      <div class="dropdown2" role="group">
														   <div class="dropdown slct_tbl pull-left sideicbb">
															<i class="fa fa-ellipsis-v"></i>  
															<ul class="dropdown-menu sidedis" style="display: none;">

													      <li><a class="sidedis sideicbb1"
															data-placement="top" title=""
															href="'.base_url().'index.php/transfers/nationality_country/'.$k['origin'].'"
															data-original-title="Edit Tour"> <i class="glyphicon glyphicon-pencil"></i>Edit
														</a</li>
													      <li><a class="sidedis sideicbb3 callDelete" id="'.$k['origin'].'"> 
													      <i class="glyphicon glyphicon-trash"></i> Delete</a></li>
													      </ul>
													      </div>
													      </div>
													      </td>
                                       					<td>'.$k['regionName'].'</td>
                                       					<td>'.$k['name'].'</td> 
                                       					<td  class="nationality_class" > <p> '.$k['include_countryNames'].'  </p> </td>
                                       					<td  class="nationality_class" > <p> '.$k['except_countryNames'].'  </p> </td>
   
                                       				';
                                       			?>
                                       			<?php 
                                       			echo '<td>'.$status.'</td>';
                                       			echo '</tr>';
                                       			$c++;
                                       		}	
                                       	}

                                       	?>
                                      	 
										 
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
	<script type="text/javascript">  
		$(document).ready(function()
		{
           
          $(".callDelete").click(function() { 
            $id = $(this).attr('id'); //alert($id);
		        $response = confirm("Are you sure to delete this record???");
		        if($response==true){ window.location='<?=base_url()?>index.php/hotel/delete_nationality_region2/'+$id; } else{}
           });
		});
        </script>
<script>
$(document).ready(function()
{
$(".callDelete").click(function() { 
$id = $(this).attr('id'); //alert($id);
    $response = confirm("Are you sure to delete this record???");
    if($response==true){ window.location='<?=base_url()?>index.php/transfers/delete_nationality_countries/'+$id; } else{}
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
      if(checkval=='')
      {
        alert('Please Select Any Nationality Country Type!!')
        return false;
      }
      var theme_tbl = 'all_nationality_country';
      var id = 'origin';
   
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

    </script>
