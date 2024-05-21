
<div id="package_types" class="bodyContent col-md-12 yhgjk">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>Nationality Group</h1></a></li>
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
											href="<?php echo base_url(); ?>index.php/activity/add_price_category">
											<button class='btn btn-primary' style='margin-bottom: 5px'>
												<i class='icon-male'></i> + Add Nationality Group
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
														<th>Actions</th>
														<th>Nationality Group</th>
														<th>Contitent</th>
														<th>Country</th>
													</tr>
												</thead>
												<tbody>
                                      <?php 
                                       
                                       if(!empty($price_category_data)){ $c=1;foreach($price_category_data as $k){

                                       	?>
                                      <tr>
														<td><?=$c;?></td>
														<td>
															
															
															<div class="dropdown2" role="group">
																<div class="dropdown slct_tbl pull-left sideicbb">
																<i class="fa fa-ellipsis-v"></i>  
																<ul class="dropdown-menu sidedis" style="display: none;">
																<li>
																<a class="sidedis sideicbb1"
																	data-placement="top" 
																	href="<?php echo base_url(); ?>activity/add_price_category/<?php echo $k->id; ?>"
																	data-original-title="Edit Tour"> <i class="fa fa-edit"></i>Edit
																</a>
																</li>
																	<li>
																		<a class='sidedis sideicbb2'
																	data-placement='top' 
																	onclick="return confirm('Are you sure, do you want to delete this record?');"
																	href='<?php echo base_url(); ?>index.php/activity/delete_package_type/<?=$k->id;?>'>
																	<i class='fa fa-trash'></i>Delete
																</a>
																	</li>
																</ul>
																</div>
															</div>
															
														</td>
														<td><?=$k->price_category_name;?></td>
														<td><?=$k->cont_name;?></td>
													<td>
														<?php 
														$output='';
														$tours_country = explode(',', $k->country);

	 		
                foreach ($tours_country as $key => $value) {
                 $output .=  $tours_country_name[$value].'<br/>';
               }
              echo $output .='</td>';
               ?>
														<!-- <td><?=$k->contient;?></td> -->
														<!-- <td><?=$k->cont_name;?></td> -->
														<!-- <td><?=$k->country;?></td> -->
														
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
