<div id="enquiries" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>View General Enquiries </h1></a></li>
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
						
						<div class='row'>
                <div class='col-sm-14'>
                  <div class='' style='margin-bottom:0;'>
                    <div class=''>
                      <div class='responsive-table'>
                        <div class='scrollable-area'>
                          <table class='data-table-column-filter table table-bordered table-striped' style='margin-bottom:0;'>
                            <thead>
                            <tr>
                            <th>S.No</th>
                            
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Message</th>
                            <th>Departure Date</th>
                            <th>Buget</th>
                            <th>Duration</th>
                            <!--<th>Status</th>-->
                            <th>Date</th>
                            <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                          <?php 
                        // debug($enquiries);exit;
                          $count = 1; 
                        foreach($enquiries as $key => $package) { 
                           
                           
                          
                           
                          ?>
                      <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $package->tname; ?></td>
                        <td><?php echo $package->email; ?></td>
                        <td><?php echo $package->phone; ?></td>
                        <td><?php echo $package->fromplace; ?></td>
                        <td><?php echo $package->toplace; ?></td>
                        <td><?php echo $package->message; ?></td>
                        <td><?php echo date('d-m-Y',strtotime($package->departure_date)); ?></td>
                        <td><?php echo $package->buget; ?></td>
                        <td><?php echo $package->duration; ?></td>
                        <td><?php echo date('d-m-Y',strtotime($package->date)); ?></td>
                      
                         <td class="center">                       
                                   <!--  <a class="btn btn-primary btn-xs has-tooltip" data-placement="top" title=""  href="<?php echo base_url(); ?>supplier/send_enq_mail/<?php echo $package->id; ?>"  data-original-title="Send mail">
                                      <i class="icon-envelope"></i>
                                    </a> -->
                                   <a href="<?php echo base_url(); ?>supplier/delete_general_enquiry/<?php echo $package->id; ?>"  data-original-title="Delete"  onclick="return confirm('Do you want delete this record');" class="btn btn-danger btn-xs has-tooltip" data-original-title="Delete"> 
                                  <i class="icon-remove">Delete</i>
                                   </a>
                        </td>
                      </tr>   
                  <?php  
                  $count++; 
                  } ?>  
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
		</div>
		<!-- PANEL BODY END -->
	</div>
	<!-- PANEL WRAP END -->
</div>
<!-- Modal -->
   <?php 
                        
                        foreach($enquiries as $key => $package) { ?>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Package enquiry</h4>
            </div>
            <div class="modal-body">
  <?php echo $package->message; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function(){
    $('.openPopup').on('click',function(){
        var dataURL = $(this).attr('data-href');
        
            $('#myModal').modal({show:true});
       
    }); 
});
</script>
