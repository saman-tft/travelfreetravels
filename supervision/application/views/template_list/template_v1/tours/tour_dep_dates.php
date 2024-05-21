<?php error_reporting(0);?>
<?php if ($this->session->flashdata('error_message') != '') { ?>
<div class="alert alert-danger alert-dismissible">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> <?=$this->session->flashdata('error_message')?>
</div>
<?php } ?>
<script src="/airliners/extras/system/library/ckeditor/ckeditor.js"></script>
<div id="Package" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<?php $package=$tour_data[0]['package_name'];?>
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active" id="add_package_li"><a
						href="#add_package" aria-controls="home" role="tab"
						data-toggle="tab"> Departure Date List : [ Tour Name : <?php echo $package;?>]</a></li>				
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
		<form
				action="<?php echo base_url(); ?>index.php/tours/tour_dep_date_save"
				method="post" enctype="multipart/form-data" id="form form-horizontal validate-form"
				class='form form-horizontal validate-form'>
				<div class="tab-content">
					<!-- Add Package Starts -->
					<div role="tabpanel" class="tab-pane active" id="add_package">
						    <div class="col-md-12">

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Add Date
								</label>
								<div class='col-sm-4 controls'>
								<input type="text" name="tour_dep_date" id="tour_dep_date" class="form-control" value="" placeholder="Choose Date" data-rule-required='true' required readonly> 
								</div>
							</div>
                           <div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Seat Count <span style="color: red;">*</span>
								</label>
								<div class='col-sm-4 controls'>
								<input type="text" name="seat_count" id="seat_count" class="form-control" value="" placeholder="Enter Seat Count" data-rule-required='true' required> 
								</div>
							</div>								
							<div class='' style='margin-bottom: 0'>
								<div class='row'>
									<div class='col-sm-9 col-sm-offset-3'>	
									    <input type="hidden" name="tour_id" value="<?=$tour_id?>">							
										<button class='btn btn-primary' type='submit'>Save</button>
          <a href="<?php echo base_url(); ?>index.php/tours/tour_list" style="color:white;" class="btn btn-primary">Go Back to Tour List</a>
									</div>
								</div>
							</div>
						</div>
						
						
					
					</div>					
				</div>
			</form>				
		</div>
		<!-- PANEL BODY END -->
	
	<!-- PANEL WRAP END -->
			
			<table class="table table-bordered">
			<thead>
				<tr>
					<th>SN</th>
					<th>Departure Date [dd-mm-yy]</th>
					<th>Allocated Seats</th>
					<th>Reserved Seats</th>
					<th>Remaining Seats</th>
					<th>Action</th>				
				</tr>
			</thead>
			<tbody>	
			<?php
        $sn = 1;
        //debug($tour_destinations);
        foreach ($tour_dep_dates as $key => $data) {  
            	 $reserved_count=0; 
        $remaining_count=0;
     $user_attributes=booked_seat_count($tour_id,$data['dep_date']);
     // debug($tour_dep_dates_list);
     if($user_attributes=='')
     {
      $remaining_count=$data['seat_count'];
     }
     else
     {
     $user_data=json_decode($user_attributes,1);
     $reserved_count=$user_data['total_adult_count'];
     $remaining_count=$data['seat_count']-($user_data['total_adult_count']+$user_data['no_of_extrabed']);
     }
       echo '<tr>
              <td>'.$sn.'</td>
              <td>'.changeDateFormatDMY($data['dep_date']).'</td>
              <td>'.$data['seat_count'].'</td>
              <td>'.$reserved_count.'</td>
              <td>'.$remaining_count.'</td>';    
              
              
        echo '<td class="center">              
              <!--<a class="" data-placement="top" href="'.base_url().'index.php/tours/delete_tour_dep_date/'.$data['id'].'/'.$tour_id.'"
              data-original-title="Delete Tour Destination"> <i class="glyphicon glyphicon-trash"></i></i> Delete
              </a>-->
              <a class="callDelete text-danger" id="'.$data['id'].'" tour-id="'.$tour_id.'"> 
              <i class="glyphicon glyphicon-trash"></i> Delete</a>
              </td>
              </tr>';
              /*<a class="" data-placement="top" href="'.base_url().'tours/edit_tour_dep_date/'.$data['id'].'/'.$tour_id.'"
              data-original-title="Edit Tour Destination"> <i class="glyphicon glyphicon-pencil"></i> Edit
              </a>*/
        $sn++;
        }
        ?>
		</tbody>
		</table>				
		</div>
		</div>

<?php
       $HTTP_HOST = '192.168.0.63';
       if(($_SERVER['HTTP_HOST']==$HTTP_HOST) || ($_SERVER['HTTP_HOST']=='localhost'))
	   {
				$airliners_weburl = '/airliners/';	 
	   }
	   else
	   {
				$airliners_weburl = '/~development/airliners_v1/';
       } 
       /*<?=$airliners_weburl?>*/          
       ?> 
<link href="<?=$airliners_weburl?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/datepicker.css" rel="stylesheet"> 
<script src="<?=$airliners_weburl?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/datepicker.js"> </script>  
<script src="<?=$airliners_weburl?>extras/system/template_list/template_v1/javascript/page_resource/datepicker/jquery.blueberry.js"> </script>  
<script type="text/javascript">  
		$(document).ready(function()
		{

			$('#tour_dep_date').datepicker({
        	minDate:0,
        	numberOfMonths: 2,
        	changeMonth: 0,
        	dateFormat: "yy-mm-dd"
        	});
			
            $(".callDelete").click(function() { 
            $id = $(this).attr('id'); //alert($id);
            $tour_id = $(this).attr('tour-id'); //alert($id);
		    $response = confirm("Are you sure to delete this record?");
		    if($response==true){ window.location='<?=base_url()?>index.php/tours/delete_tour_dep_date/'+$id+'/'+$tour_id; } else{}
           });
		});
        </script>
<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="http://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script> $(function () { $('.table').DataTable(); }); </script>                
