
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
											href="<?php echo base_url(); ?>index.php/activity/nationality_country">
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
														<th>Contitent</th> 
														<th>Nationality Type</th>
														<th>Included Countries</th>
														<th>Except Countries</th>
														<th>Actions</th>
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
                                       			echo '
                                       				<tr>
                                       					<td>'.$c.'</td>
                                       					<td>'.$k['regionName'].'</td>
                                       					<td>'.$k['name'].'</td> 
                                       					<td  class="nationality_class" > <p> '.$k['include_countryNames'].'  </p> </td>
                                       					<td  class="nationality_class" > <p> '.$k['except_countryNames'].'  </p> </td>
   
                                       				';
                                       			?>

                                       			<td>
													<div class=''>
														<a class="btn btn-primary btn-xs has-tooltip"
															data-placement="top" title=""
															href="<?php echo base_url(); ?>index.php/activity/nationality_country/<?php echo $k['origin'] ?>"
															data-original-title="Edit Tour"> <i class="icon-edit"></i>Edit
														</a> &nbsp;
  <a class="sidedis sideicbb3 callDelete" id="<?php echo $k['origin'] ?>"> 
                                    <i class="glyphicon glyphicon-trash"></i> Delete</a>
														<!-- <a class='btn btn-danger btn-xs has-tooltip'
															data-placement='top' title='Delete'
															onclick="return confirm('Are you sure, do you want to delete this record?');"
															href='<?php echo base_url(); ?>index.php/activity/delete_package_type/<?=$k->id;?>'>
															<i class='icon-remove'></i>Delete
														</a> -->
													</div>
												</td>

                                       			<?php 
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
