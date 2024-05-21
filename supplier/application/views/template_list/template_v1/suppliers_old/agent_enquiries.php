<div id="enquiries" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<div class="panel-heading">
			<!-- PANEL HEAD START -->
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
					<li role="presentation" class="active"><a href="#fromList"
						aria-controls="home" role="tab" data-toggle="tab"><h1>View Agent Enquiries </h1></a></li>
					<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
				</ul>
			</div>
		</div>
		<!-- PANEL HEAD START -->
		<div class="panel-body">
			<!-- PANEL BODY START -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="fromList">
					<div class="col-md-12 nopad">
						<div class='row'>
						
						<div class='row'>
                <div class='col-sm-12'>
                  <div class='' style='margin-bottom:0;'>
                    <div class=''>
                      <div class='responsive-table'>
                        <div class='scrollable-area'>
                          <table class='data-table-column-filter table table-bordered table-striped' style='margin-bottom:0;'>
                            <thead>
                              <tr>
                              <th>S.No</th>
                              <th>Package Name</th>
                              <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Best Time To call</th>
                            <th>Package</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Date</th>
                             <th>Comments</th>
                            <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                          <?php 
                        //debug($enquiries);exit;
                          $count = 1; 
                        foreach($enquiries as $key => $package) { 
                           $status_val = $package->pack_status;
                           // debug($enquiries);exit;
                          if(!empty($enquiries)) {
                            // strip tags to avoid breaking any html
                            $string = strip_tags($package->message);
                            if (strlen($string) > 200) {
                                // truncate string
                                $stringCut = substr($string, 0, 200);

                                // make sure it ends in a word so assassinate doesn't become ass... 
                                $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'... <a href="javascript:void(0);" class="openPopup">Read More</a>'; 
                            }

                            if($package->best_time_to_call == ''){
                              $package->best_time_to_call = 'N/A';
                            }
                            if($package->kind_pkg_uwant == ''){
                              $package->kind_pkg_uwant = 'N/A';
                            }
                           
                          ?>
                      <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $package->package_name?></td>
                        <td><?php echo $package->first_name; ?></td>
                        <td><?php echo $package->email; ?></td>
                        <td><?php echo $package->phone; ?></td>
                        <td><?php echo $package->best_time_to_call; ?></td>
                        <td><?php echo $package->kind_pkg_uwant; ?></td>
                        <td><?php echo $string; ?></td>
                       <td>
                              <select class="status" data-id="<?php echo $package->id; ?>">
                                <option value="0" <?= $status_val=='0'?'selected':'' ?>>NEW</option>
                                <option value="1" <?= $status_val=='1'?'selected':'' ?>>CONTACTED</option>
                                <option value="2" <?= $status_val=='2'?'selected':'' ?>>CLOSED</option>
                              </select>
                        </td>
                        <td><?php echo $package->date; ?></td> 
                        <td> <textarea data-id="<?php echo $package->id; ?>" class="comments" maxlength="50" style="resize: none;"><?php echo $package->comments; ?></textarea></td>
                         <td class="center">                       
                                   <!--  <a class="btn btn-primary btn-xs has-tooltip" data-placement="top" title=""  href="<?php echo base_url(); ?>supplier/send_enq_mail/<?php echo $package->id; ?>"  data-original-title="Send mail">
                                      <i class="icon-envelope"></i>
                                    </a> -->
                                   <a href="<?php echo base_url(); ?>index.php/supplier/delete_agent_enquiry/<?php echo $package->id; ?>/<?php echo $package->package_id; ?>"  data-original-title="Delete"  onclick="return confirm('Do you want delete this record');" class="btn btn-danger btn-xs has-tooltip" data-original-title="Delete"> 
                                  <i class="icon-remove">Delete</i>
                                   </a>
                        </td>
                      </tr>   
                  <?php  } 
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
    $('.status').on('change',function(){
       var status = $(this).val();
       var enquiry_id = $(this).attr('data-id');
        $.ajax({
        method: "POST",
        url: "<?php echo base_url(); ?>index.php/supplier/agent_enquiry_status",
        data: { enquiry_id:enquiry_id, status: status }
        })
        .done(function( msg ) { 
          alert(msg);
        });
       
    }); 

    $('.comments').on('change',function(){
        var comments = $(this).val();
        var enquiry_id = $(this).attr('data-id');
        $.ajax({
        method: "POST", 
        url: "<?php echo base_url(); ?>index.php/supplier/agent_update_comments",
        data: { enquiry_id:enquiry_id, comments: comments }
        })
        .done(function( msg ) { 
          alert(msg);
        }); 
      }); 
});
</script>
