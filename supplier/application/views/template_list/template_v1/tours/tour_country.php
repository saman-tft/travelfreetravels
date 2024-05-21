<?php if ($this->session->flashdata('error_message') != '') { ?>
<div class="alert alert-danger alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> <?=$this->session->flashdata('error_message')?>
</div>
<?php } ?>
<?php if ($this->session->flashdata('success_message') != '') { ?>
<div class="alert alert-success alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Success!</strong> <?=$this->session->flashdata('success_message')?>
</div>
<?php } ?>	
<div id="Package" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active" id="add_package_li"><a
						href="#add_package" aria-controls="home" role="tab"
						data-toggle="tab">Tour Country </a></li>
			        <li aria-controls="home"> &nbsp;&nbsp;
					<button class='btn btn-primary' onclick="$('.form').slideToggle();">Add</button>
				    </li>	
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<form
				action="<?php echo base_url(); ?>index.php/tours/tour_country_save"
				method="post" enctype="multipart/form-data" id="form form-horizontal validate-form"
				class='form form-horizontal validate-form' style="display:none;">
				<div class="tab-content">
					<!-- Add Package Starts -->
					<div role="tabpanel" class="tab-pane active" id="add_package">
						   <div class="col-md-12">
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Choose Region
								</label>
								<div class='col-sm-8 controls'>
								<select class='select2 form-control' data-rule-required='true' name='continent' id="continent" data-rule-required='true' required>
         <option value="">Choose Region</option>
         <?php
         foreach($tour_region as $k => $v)
         {
         	echo '<option value="'.$v['id'].'">'.$v['name'].' </option>';
         }
         ?>
								</select>				
							</div>
							</div>
							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Tour Country Name
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" name="tour_country" id="tour_country"
										placeholder="Enter Tour Country Name" data-rule-required='true'
										class='form-control' required>									
								</div>
							</div>											
							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>								
										<button class='btn btn-primary' type='submit'>Save</button>
									</div>
								</div>
							</div>
						</div>																	
					</div>					
				</div>
			</form>			
		</div>	
			<div class="table-responsive scroll_main" style="overflow-hidden; overflow-x:scroll;">
			<table class="table table-bordered">
			<thead>
				<tr>
					<th>Sl.No</th>
					<th>Tour Region</th>
					<th>Tour Country</th>
					<th>Current Status</th>
		            <th>Status Change</th>	
		            <th>Action</th>				
				</tr>
		    </thead>
		    <tbody>		
				<?php
        $sn = 1;
        foreach ($tour_country as $key => $data) {
        if($data['status']==1)
        {
           $status = '<span style="color:green;">Active</span>';
        }
        else
        {
           $status = '<span style="color:red;">In-Active</span>';
        }
        echo '<tr>
              <td>'.$sn.'</td>
              <td>'.$data['continent_name'].'</td>
              <td>'.$data['country_name'].'</td>';
        echo '<td>'.$status.'</td>';
        if($data['status']==1)
        {
        echo '<td>
              <a class="" data-placement="top" href="'.base_url().'index.php/tours/activation_country/'.$data['id'].'/0"
              data-original-title="Deactivate Tour Destination"> <i class="glyphicon glyphicon-th-large"></i></i> De-activate
              </a>
              </td>';
        }
        else
        {
        echo '<td>
              <a class="" data-placement="top" href="'.base_url().'index.php/tours/activation_country/'.$data['id'].'/1"
              data-original-title="Activate Tour Destination"> <i class="glyphicon glyphicon-th-large"></i></i> Activate
              </a>
              </td>';
        }      
        echo '<td class="center">
              <a class="" data-placement="top" href="'.base_url().'index.php/tours/edit_tour_country/'.$data['id'].'"
              data-original-title="Edit Tour Destination"> <i class="glyphicon glyphicon-pencil"></i> Edit
              </a>
              </td>
              </tr>';
        $sn++;
        }
        ?>
		</tbody>
		</table>
		</div>				
		</div>
		</div>
<script type="text/javascript" src="<?=base_url()?>extras/system/template_list/template_v1/javascript/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?=base_url()?>extras/system/template_list/template_v1/javascript/js/tiny_mce/tiny_mce_call.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="http://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script> $(function () { $('.table').DataTable(); }); </script>
