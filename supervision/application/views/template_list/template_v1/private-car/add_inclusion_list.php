<div id="package_types" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>Add / Edit
								Priced Coverage</h1></a></li>
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
								<div class=''>
									<div class='box-header '>
										<div class='title' id="tab1">  <?php if(isset($status)){echo $status;}?></div>
										<div class='actions'></div>
									</div>
									<div class=''>
										<form class='form form-horizontal validate-form row'
											style='margin-bottom: 0;'
											action="<?php echo base_url(); ?>index.php/privatecar/save_inclusion_list"
											method="post" name="frm1" enctype="multipart/form-data">
											<div class='form-group col'>
												<label class='control-label col-sm-2' for='validation_name'>Title</label>
												<div class='col-sm-3 controls'>
													<input type="hidden" name="package_id"
														value="<?=@$id;?>"> <input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='pname' name='title'
														value="<?=@$pack_data[0]->title;?>"
														placeholder='title' type='text' required>
												</div>
											</div>
											<div class='form-group col'>
												<label class='control-label col-sm-2' for='validation_name'>Content</label>
												<div class='col-sm-3 controls'>
												        <input
														class='form-control' data-rule-minlength='2'
														data-rule-required='true' id='pname' name='content'
														value="<?=@$pack_data[0]->content;?>"
														placeholder='content' type='text' required>
												</div>
											</div>
											<div class='form-actions' style='margin-bottom: 0'>
												<div class='row'>
													<div class='col-sm-9 col-sm-offset-2'>
														<a
															href="<?php echo base_url(); ?>index.php/privatecar/extra_services">
															<button class='btn btn-primary' type='button'>
																<i class='icon-reply'></i> Back
															</button>
														</a>&nbsp;
														<button class='btn btn-primary' type='submit'>
															<i class='icon-save'></i> Save
														</button>
													</div>
												</div>
											</div>
										</form>
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
														
														<th>Title</th>
														<th>Content</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
                                      <?php if(!empty($package_view_data)){ $c=1;foreach($package_view_data as $k){?>
                                      <tr>
														<td><?=$c;?></td>
														<td><?=$k->title;?></td>
														<td><?=$k->content;?></td>
														<td>
															<div class=''>
																<a class="btn btn-primary btn-xss has-tooltip"
																	data-placement="top" title=""
																	href="<?php echo base_url(); ?>index.php/privatecar/edit_inclusion_list/<?php echo $k->incl_id; ?>"
																	data-original-title="Edit Tour"> <i class="icon-edit"></i>Edit
																</a> &nbsp;<a class='btn btn-danger btn-xss has-tooltip'
																	data-placement='top' title='Delete'
																	onclick="return confirm('Are you sure, do you want to delete this record?');"
																	href='<?php echo base_url(); ?>index.php/privatecar/delete_inclusion_list/<?=$k->incl_id;?>/<?=$id;?>'>
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
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
</div>