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
						aria-controls="home" role="tab" data-toggle="tab"><h1>Extra Service</h1></a></li>
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
											href="<?php echo base_url(); ?>index.php/privatecar/add_extra_service">
											<button class='btn btn-primary' style='margin-bottom: 5px'>
												<i class='icon-male'></i> + Add Extra Service
											</button>
										</a> <a class="btn box-collapse btn-xss btn-link" href="#"><i></i></a>
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
														<th>Service Name</th>
														<th>name</th>
														<th>EquipType</th>
														<th>Amount</th>
														<th>DetailedInformation</th>
															<th>Underwriter</th>
													     <th>Disclaimer</th>
														<th>PolicyUrl</th>
														<th>InsuranceSupplier</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
                                      <?php if(!empty($package_view_data)){ $c=1;foreach($package_view_data as $k){?>
                                      <tr>
														<td><?=$c;?></td>
														<td><?=$k->PolicyName;?></td>
														<td><?=$k->name;?></td>
														<td><?=$k->EquipType;?></td>
														<td><?=$k->Amount;?></td>
														<td><?=$k->DetailedInformation;?></td>
														<td><?=$k->Underwriter;?></td>
														<td><?=$k->Disclaimer;?></td>
														<td><?=$k->PolicyUrl;?></td>
														<td><?=$k->InsuranceSupplier;?></td>
														<td>
															<div class=''>
															    <?php
															    if($k->PolicyName=="Full Protection")
															    {
															    ?>
															    <a class="btn btn-primary btn-xss has-tooltip"
																	data-placement="top" title=""
																	href="<?php echo base_url(); ?>index.php/privatecar/add_inclusion_list/<?php echo $k->Equipid; ?>"
																	data-original-title="Edit Tour"> <i class="icon-edit"></i>Add Inclusion List
																</a> 
																<?php
                                      }
																?>
												
																&nbsp;
																<a class="btn btn-primary btn-xss has-tooltip"
																	data-placement="top" title=""
																	href="<?php echo base_url(); ?>index.php/privatecar/add_extra_service/<?php echo $k->Equipid; ?>"
																	data-original-title="Edit Tour"> <i class="icon-edit"></i>Edit
																</a> &nbsp;<a class='btn btn-danger btn-xss has-tooltip'
																	data-placement='top' title='Delete'
																	onclick="return confirm('Are you sure, do you want to delete this record?');"
																	href='<?php echo base_url(); ?>index.php/privatecar/delete_extra_service/<?=$k->Equipid;?>'>
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
