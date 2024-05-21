 
<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="http://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script> $(function () { $('.table').DataTable(); }); </script>
<?php if($this->session->flashdata('region_msg')){?>
    <div class="alert alert-danger">      
      <?php echo $this->session->flashdata('region_msg')?>
    </div>
    <?php } ?>
<?php error_reporting(0);?>
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
						data-toggle="tab">Regions </a></li>
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
				action="<?php echo base_url(); ?>index.php/transfers/region_save"
				method="post" enctype="multipart/form-data" id="form form-horizontal validate-form"
				class='form form-horizontal validate-form' style="display:none;">
				<div class="tab-content">
					<!-- Add Package Starts -->
					<div role="tabpanel" class="tab-pane active" id="add_package">
						   <div class="col-md-12">

							<div class='form-group'>
								<label class='control-label col-sm-3' for='validation_current'>Region
								</label>
								<div class='col-sm-8 controls'>
									<input type="text" name="tour_region" id="tour_region"
										placeholder="Enter Region" data-rule-required='true'
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
		<!-- PANEL BODY END -->
	
	<!-- PANEL WRAP END -->
	
			<div class="table-responsive scroll_main" style="overflow-hidden; overflow-x:scroll;">
			<table class="table table-bordered">
			<thead>
				<tr>
					<th>Sl.No</th>	
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
		            <th>Action</th>	
					<th>Region</th>
					<th>Current Status</th>
		            <th>Status Change</th>			
				</tr>
		    </thead>
		    <tbody>		
				<?php
        $sn = 1;
        //debug($tour_destinations);
        foreach ($nationality_region as $key => $data) {
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
              <td><input type="checkbox" class="interested'.$j.'"  id="interested_'.$j.'_'.$sn.'" onclick="uncheck('.$j.');" value="'.$data['id'].'" /></td>
              <td class="center">
              <div class="dropdown2" role="group">
			   <div class="dropdown slct_tbl pull-left sideicbb">
				<i class="fa fa-ellipsis-v"></i>  
				<ul class="dropdown-menu sidedis" style="display: none;">

              <li><a class="sidedis sideicbb1" data-placement="top" href="'.base_url().'index.php/transfers/edit_nationality_region/'.$data['id'].'"
              data-original-title="Edit Tour Destination"> <i class="glyphicon glyphicon-pencil"></i> Edit
              </a> </li>
              <li><a class="sidedis sideicbb3 callDelete" id="'.$data['id'].'"> 
              <i class="glyphicon glyphicon-trash"></i> Delete</a></li>
              </ul>
              </div>
              </div>
              </td>
              <td>'.$data['name'].'</td>';
        //echo '<td>'.$galleryImages.'</td>';
        echo '<td>'.$status.'</td>';
        if($data['status']==1)
        {
        echo '<td>
              <a class="" data-placement="top" href="'.base_url().'index.php/transfers/activation_nationality_region/'.$data['id'].'/0"
              data-original-title="Deactivate Region"> <i class="glyphicon glyphicon-th-large"></i></i> De-activate
              </a>
              </td>';
        }
        else
        {
        echo '<td>
              <a class="" data-placement="top" href="'.base_url().'index.php/transfers/activation_nationality_region/'.$data['id'].'/1"
              data-original-title="Activate Region"> <i class="glyphicon glyphicon-th-large"></i></i> Activate
              </a>
              </td>';
        }      

        echo '</tr>';
        $sn++;
        }
        ?>
		</tbody>
		</table>
		</div>				
		</div>
		</div>
		<script type="text/javascript">  
		$(document).ready(function()
		{
           
          $(".callDelete").click(function() { 
            $id = $(this).attr('id'); //alert($id);
		        $response = confirm("Are you sure to delete this record???");
		        if($response==true){ window.location='<?=base_url()?>index.php/transfers/delete_nationality_region/'+$id; } else{}
           });
		});
        </script>
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
<script type="text/javascript" src="<?=$airliners_weburl?>extras/system/template_list/template_v1/javascript/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?=$airliners_weburl?>extras/system/template_list/template_v1/javascript/js/tiny_mce/tiny_mce_call.js"></script>
<!--
<script src="/chariot/extras/system/template_list/template_v1/javascript/js/nicEdit/nicEdit.js"></script>
<script type="text/javascript" src="/chariot/extras/system/template_list/template_v1/javascript/js/nicEdit/nicEdit_call.js"></script>

<link rel="stylesheet" href="/chariot/extras/system/template_list/template_v1/javascript/js/datatables/tables.css">
<script src="/chariot/extras/system/template_list/template_v1/javascript/js/datatables/jquery.dataTables.js"></script>
<script src="/chariot/extras/system/template_list/template_v1/javascript/js/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
            $(document).ready(function() {
                $('.table').dataTable();	
            });
</script>
-->

<!-- <script type="text/javascript" src="/chariot/extras/system/template_list/template_v1/javascript/js/nicEdit-latest.js"></script> 
<script type="text/javascript">
//<![CDATA[
bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
//]]>
</script> -->
<script type="text/javascript">
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
        alert('Please Select Any Region!!')
        return false;
      }
      var theme_tbl = 'all_nationality_region';
      var id = 'id';
   
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
</script>