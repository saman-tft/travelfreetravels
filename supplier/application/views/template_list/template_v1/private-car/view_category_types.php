<?php if ($this->session->flashdata('error_message') != '') { ?>
<div class="alert alert-danger alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> <?=$this->session->flashdata('error_message')?>
</div>
<?php } ?>
<div id="package_types" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>Vehicle Category</h1></a></li>
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
											href="<?php echo base_url(); ?>index.php/privatecar/add_category_type">
											<button class='btn btn-primary' style='margin-bottom: 5px'>
												<i class='icon-male'></i> + Add Vehicle Category</button>
										</a> 
										<p style="color:green">General Information : Age is set so that drivers over 30 years old may only rent superior
car categories, such as Full Size or Premium/Luxury cars.A young driver surcharge generally applies to drivers up to the age of 30 years</p>
<p style="color:green">For drivers over the age of 70 years, a senior driver surcharge or alternatively the purchase of an extra insurance may apply</p>
									</div>
								</div>
								<div class=''>
									<div class='responsive-table'>
										<div class='scrollable-area'>
											<table
												class='data-table-column-filter table table-bordered table-striped sstabl'
												style='margin-bottom: 0;'>
												<thead>
													<tr>
														<th>S.No</th>
														<th>Vehicle Category</th>
														<th>Minimium Age</th>
														<th>Maximium Age</th>
														<th>Young Driver SurCharge(Age less than 30)(USD)</th>
														<th>Senior Driver SurCharge(Age greater than 65)(USD)</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
                                      <?php if(!empty($package_view_data)){ $c=1;foreach($package_view_data as $k){?>
                                      <tr>
														<td><?=$c;?></td>
														<td><?=$k->category_name;?></td>
														<td><?=$k->minimium_age;?></td>
														<td><?=$k->maximium_age;?></td>
														<td><?=$k->Young_driver_charge;?></td>
														<td><?=$k->Senior_driver_charge;?></td>
														<td>
															<div class=''>
																<a class="btn btn-primary btn-xss has-tooltip"
																	data-placement="top" title=""
																	href="<?php echo base_url(); ?>index.php/privatecar/add_category_type/<?php echo $k->category_id; ?>"
																	data-original-title="Edit Tour"> <i class="icon-edit"></i>Edit
																</a> &nbsp;<a class='btn btn-danger btn-xss has-tooltip'
																	data-placement='top' title='Delete'
																	onclick="return confirm('Are you sure, do you want to delete this record?');"
																	href='<?php echo base_url(); ?>index.php/privatecar/delete_category_type/<?=$k->category_id;?>'>
																	<i class='icon-remove'></i>Delete
																</a>
															</div>
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
