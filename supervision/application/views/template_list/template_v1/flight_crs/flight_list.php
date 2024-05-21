<?php
// debug($data);
?>
<style type="text/css">
a.act {
    cursor: pointer;
}

.table {
    margin-bottom: 0;
}

.modal-footer {
    padding: 10px;
}

.toggle.ios,
.toggle-on.ios,
.toggle-off.ios {
    border-radius: 20px;
}

.toggle.ios .toggle-handle {
    border-radius: 20px;
}


a.act {
    background: #0e1938;
    line-height: 21px;
    color: #fff;
    margin-bottom: 3px;
    display: inline-table;
    min-width: 82px;
    padding: 5px;
    font-size: 11px;
    text-align: center;
    border-radius: 3px;
}

a.flight_details {
	background: #0e1938;
    line-height: 21px;
    color: #fff;
    margin-bottom: 3px;
    display: inline-table;
    min-width: 82px;
    padding: 5px;
    font-size: 11px;
    text-align: center;
    border-radius: 3px;
}

a.update_details {
    background: #0e1938;
    line-height: 15px;
    color: #fff;
    margin-bottom: 3px;
    display: inline-table;
    min-width: 82px;
    padding: 5px;
    font-size: 11px;
    text-align: center;
    border-radius: 3px;
}
</style>
<!-- HTML BEGIN -->
<div class="bodyContent">
    <div class="panel panel-default">
        <!-- PANEL WRAP START -->
        <div class="panel-heading">
            <!-- PANEL HEAD START -->
            <div class="panel-title">
                Flight List
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="panel-body">

            <!-- Dinesh Start 19 -02-2018-->
            <h4>Advanced Search Panel</h4>


            <!-- End 19 -02-2018 -->
            <div class="table-responsive">
                <form method="GET" autocomplete="off">

                    <div class="clearfix form-group">

                        <div class="col-xs-4">
                            <label>
                                Departure Airport Code
                            </label>
                            <input type="text" class="form-control getAiportlist" placeholder="Departure Airport"
                                name="dep_origin" value="<?=$this->input->get('dep_origin');?>" />

                        </div>
                        <div class="col-xs-4">
                            <label>
                                Arrival Airport Code
                            </label>
                            <input type="text" class="form-control getAiportlist" placeholder="Departure Airport"
                                name="arival_origin" value="<?=$this->input->get('arival_origin');?>" />

                        </div>
                        <div class="col-xs-4">
                            <?php $month_list = generate_month_list(); ?>
                            <label>
                                Month
                            </label>
                            <select class="form-control" name="month">
                                <option value="">Month </option>
                                <?php
								
								foreach ($month_list as $key => $value) {
									$select = '';
									if(($this->input->get('month')==$key)){
										$select = "selected";
									}
									echo '<option value="'.$key.'" '.$select.'>'.$value.'</option>';
								}
								?>
                            </select>
                        </div>

                        <div class="col-xs-4">
                            <label>
                                Year
                            </label>
                            <select class="form-control" name="year">
                                <option value="">Year </option>
                                <?php
								$c_year = date('Y');
								for ($i=0; $i < 2; $i++) { 

									$select = '';
									if(($this->input->get('year')==$c_year)){
										$select = "selected";
									}

									echo '<option value="'.$c_year.'" '.$select.'>'.$c_year.'</option>';
									$c_year = $c_year-1;
								}
								?>
                            </select>
                        </div>

                    </div>
                    <div class="col-sm-12 well well-sm">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a class="btn btn-warning" href="<?php echo base_url().'flight_crs/flight_list' ?>">Reset</a>
                    </div>
                </form>

                <!-- PANEL BODY START -->
                <table class="table table-bordered table-hover table-condensed" id="tab_flight_list">
                    <thead>
                        <tr>
                            <th width="4%"><i class="fa fa-sort-numeric-asc"></i> SNo</th>
                            <!-- 					<th>Origin</th>
<th>Destination</th> -->
                            <th width="12%">Departure From Datetime</th>
                            <th width="12%">Departure To Datetime</th>
                            <th width="10%">Flight Number</th>
                            <th width="10%">Carrier Code</th>
                            <th width="10%">Airline Name</th>
                            <th width="10%">Class</th>
                            <th width="12%">No. Of Stops</th>
                            <th width="12%">Baggage</th>
                            <th width="12%">Meals</th>
                            <th width="12%">Extra</th>
                            <th width="10%">Status</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>





                        <?php
			
			$temp_origin = "temp_origin";
			$temp_destination = "temp_destination";
			$temp_flight_num = "temp_flight_num";
			$temp_carrier_code = "temp_carrier_code";
			$temp_class = "temp_class";
					$arrComp = array();
					if(count($data)>0){
						$filter_data = base64_encode( json_encode($_GET));
						
						$s_f_d = 1;
						foreach($data as $key => $flight_list_details){
							//debug($flight_list_details['origin']);
							//debug($temp_origin);
							$strData_comp = $flight_list_details['origin']."_".$flight_list_details['destination']."_".$flight_list_details['destination']."_".$flight_list_details['flight_num']."_".$flight_list_details['carrier_code']."_".$flight_list_details['class_type'];
							if(!in_array($strData_comp,$arrComp)){
							//	$arrComp[] = $strData_comp;
								
								echo '<tr style="background: #fff;">
						             <td colspan="6"><strong>'.$flight_list_details['origin'].' - '.$flight_list_details['destination'].' <span style="font-weight: normal;">('.$flight_list_details['carrier_code'].''.$flight_list_details['flight_num'].')</span></strong></td>
						             <td colspan="6" align="right"><a class="btn btn-primary btn-sm  show-bal-btn" href="'.base_url().'index.php/flight_crs/update_flight_details/'.$flight_list_details['fsid'].'/'.$filter_data.'" >Update Flight Details</a></td>
						          </tr>';

						        $temp_origin = $flight_list_details['origin'];
						        $temp_destination = $flight_list_details['destination'];
						        $temp_flight_num = $flight_list_details['flight_num'];
						        $temp_carrier_code = $flight_list_details['airline_name'];
						        $temp_class = $flight_list_details['class_type'];
							}

								$dep_from_date	=  date('d-M-Y',strtotime($flight_list_details['dep_from_date']));
								$departure_time	= date('H:i',strtotime($flight_list_details['departure_time']));

								$dep_to_date	=  date('d-M-Y',strtotime($flight_list_details['dep_to_date']));
								$arrival_time	= date('H:i',strtotime($flight_list_details['arrival_time']));

								echo ' <tr style="background: #e6f2f5;">
						               <td> 
							               <span disply:block;>'.$s_f_d++.'</span>
						               </td>
						               <td >'.$dep_from_date.' '.$departure_time.'</td>
						               <td >'.$dep_to_date.' '.$arrival_time.'</td>
						              
						               <td >'.$flight_list_details['flight_num'].'</td>
						               <td >'.$flight_list_details['airline_name'].'</td>
						               <td >'.$flight_list_details['airline_name'].'</td>
						               <td >'.$flight_list_details['class_type'].'</td>
						               <td >'.$flight_list_details['no_of_stops'].'</td>
						               <td >'.$flight_list_details['checkin_baggage'].'</td>
						               <td >'.$flight_list_details['meals'].'</td>
						               <td >'.$flight_list_details['extra'].'</td>
						             ';
						             ?>
                        <td>
                            <!-- <button type="button" class="btn btn-sm btn-toggle stus dyna_status <?=($flight_list_details['active'] == 1)?'active' : '' ?> act-<?=$flight_list_details['fsid']?>" data-toggle="button" aria-pressed="<?=($flight_list_details['active'] == 1)?'true' : 'false' ?>" data-fsid="<?=$flight_list_details['fsid']?>" data-status="<?=$flight_list_details['active']?>" autocomplete="off">
				   					<div class="handle"></div>
									</button> -->

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox"
                                        <?php if($flight_list_details['active'] == 1) echo "checked"; ?>
                                        class="stus dyna_status <?=($flight_list_details['active'] == 1)?'active' : '' ?> act-<?=$flight_list_details['fsid']?>"
                                        aria-pressed="<?=($flight_list_details['active'] == 1)?'true' : 'false' ?>"
                                        data-fsid="<?=$flight_list_details['fsid']?>"
                                        data-status="<?=$flight_list_details['active']?>" autocomplete="off" value="">


                                </label>
                            </div>
                            <!--      <?php if($flight_list_details['active'] == 1) {?>
        <center><label class="btn btn-success btn-xs"  style="background-color: #28a745 !important; border: none; border-radius: 3px;">Active</label></center>
    <?php } else {?>

       <center> <label class="btn btn-success btn-xs" style="background-color: #dc3545 !important; border: none; border-radius: 3px;">InActive</label></center>
    <?php } ?> -->

                        </td>
                        <td><a class="act" data-adult_basefare="<?=$flight_list_details['adult_basefare']?>"
                                data-adult_tax="<?=$flight_list_details['adult_tax']?>"
                                data-child_basefare="<?=$flight_list_details['child_basefare']?>"
                                data-child_tax="<?=$flight_list_details['child_tax']?>"
                                data-infant_basefare="<?=$flight_list_details['infant_basefare']?>"
                                data-infant_tax="<?=$flight_list_details['infant_tax']?>"
                                data-infant_selling="<?=$flight_list_details['infant_selling_fare']?>"
                                data-child_selling="<?=$flight_list_details['child_selling_fare']?>"
                                data-adult_selling="<?=$flight_list_details['adult_selling_fare']?>">Fare Details</a>
                            <br> <a class="flight_details" data-fsid="<?=$flight_list_details['fsid']?>"
                                href="javascript::void(0)">Flight Details</a><br>
                            <!-- <a href="<?=base_url();?>index.php/flight/update_flight_details/<?=$flight_list_details['fsid']?>" >Update Flight Details</a> -->
                            <?php
									//	if(in_array($flight_list_details['fsid'], $fsid)){
									//	}else{ ?>
                            <a class="update_details"
                                href="<?=base_url();?>index.php/flight_crs/delete_flight_details/<?=$flight_list_details['fsid']?>">Delete
                                Flight Details</a>
                            <?php //}
									?>

                        </td>
                        <?php 
						        echo '</tr>';
							
							//debug($flight_list_details); exit;
						}
					//	exit;
					}else{
						echo '<tr>
					<td colspan="12"> <strong>No flights available.</strong></td>
				</tr>';
					}
			?>


                        <!-- new view -->



                        <?php 
				//debug($data);exit();
				if(0){
				foreach($data as $key => $flight_list_details){ 
					if($flight_list_details['active']==1)
						$chk = "checked";
					else
						$chk = "";
					?>
                        <?php debug($flight_list_details);exit(); ?>
                        <tr>
                            <td> <?=$key+1?></td>
                            <!-- <td><?=$flight_list_details['origin']?></td>
					<td><?=$flight_list_details['destination']?></td> -->
                            <td><?=date('d-m-Y',strtotime($flight_list_details['dep_from_date']))?>
                                <?=date('H:i',strtotime($flight_list_details['departure_time']))?>
                            </td>
                            <td><?=date('d-m-Y',strtotime($flight_list_details['dep_to_date']))?>
                                <?=date('H:i',strtotime($flight_list_details['arrival_time']))?>
                            </td>
                            <td><?=$flight_list_details['flight_num']?></td>
                            <td><?=$flight_list_details['carrier_code']?></td>
                            <td><?=$flight_list_details['airline_name']?></td>
                            <td><?=$flight_list_details['class_type']?></td>
                            <td><?=$flight_list_details['no_of_stops']?></td>
                            <td><button type="button"
                                    class="btn btn-sm btn-toggle stus dyna_status <?=($flight_list_details['active'] == 1)?'active' : '' ?> act-<?=$flight_list_details['fsid']?>"
                                    data-toggle="button"
                                    aria-pressed="<?=($flight_list_details['active'] == 1)?'true' : 'false' ?>"
                                    data-fsid="<?=$flight_list_details['fsid']?>"
                                    data-status="<?=$flight_list_details['active']?>" autocomplete="off">
                                    <div class="handle"></div>
                                </button>
                            </td>

                            <td><a class="act" data-adult_basefare="<?=$flight_list_details['adult_basefare']?>"
                                    data-adult_tax="<?=$flight_list_details['adult_tax']?>"
                                    data-child_basefare="<?=$flight_list_details['child_basefare']?>"
                                    data-child_tax="<?=$flight_list_details['child_tax']?>"
                                    data-infant_basefare="<?=$flight_list_details['infant_basefare']?>"
                                    data-infant_tax="<?=$flight_list_details['infant_tax']?>">Fare Details</a> <br> <a
                                    class="flight_details" data-fsid="<?=$flight_list_details['fsid']?>"
                                    href="javascript::void(0)">Flight Details</a><br>
                                <a class="update_details"
                                    href="<?=base_url();?>index.php/flight_crs/update_flight_details/<?=$flight_list_details['fsid']?>">Update
                                    Flight Details</a>
                            </td>

                        </tr>
                        <?php 
				} }else{ ?>


                        <?php }?>
                    </tbody>
                </table>

                <?php 
			?>
            </div>
        </div>
        <!-- PANEL BODY END -->
    </div>
    <!-- PANEL WRAP END -->
</div>
<!-- HTML END -->

<div id="action" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title dyn_title" id="dynamic_text"></h4>
            </div>
            <div class="modal-body action_details">
                <div class="table-responsive fare_data dyn_data">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<script>
/*   $(function() {
    $('#toggle').bootstrapToggle({
      on: 'Enabled',
      off: 'Disabled'
    });
  }) */
</script>

<script type="text/javascript">
$(document).on('click', '.dyna_status', function() {
    var thisss = $(this);
    var fsid = $(this).data('fsid');
    var status = $(this).attr('data-status');
    if (parseInt(status) === parseInt(1)) {
        status = 0;
    } else {
        status = 1;
    }
    $.ajax({
        url: "<?=base_url();?>index.php/flight_crs/get_flight_status/" + fsid + "/" + status,
        async: false,
        success: function(result) {

            thisss.attr('data-status', status);

            toastr.success('Status Updated Successfully');
        }
    });
});

$(document).ready(function() {


    $(".act").click(function() {
        var adult_basefare = $(this).data('adult_basefare');
        var adult_tax = $(this).data('adult_tax');
        var child_basefare = $(this).data('child_basefare');
        var child_tax = $(this).data('child_tax');
        var infant_basefare = $(this).data('infant_basefare');
        var infant_tax = $(this).data('infant_tax');
        var adult_selling = $(this).data('adult_selling');
        var child_selling = $(this).data('child_selling');
        var infant_selling = $(this).data('infant_selling');

        var total_adult_fare = adult_tax + adult_selling;
        var total_child_fare = child_tax + child_selling;
        var total_infant_fare = infant_tax + infant_selling;


        var str =
            '<table class="table table-bordered"><thead><tr><th></th><th>Adult</th><th>Child</th><th>Infant</th></tr></thead><tbody><tr><td><strong>Base Fare</strong></td><td>' +
            adult_basefare + '</td><td>' + child_basefare + '</td><td>' + infant_basefare +
            '</td></tr><tr><td><strong>Selling Fare</strong></td><td>' + adult_selling + '</td><td>' +
            child_selling + '</td><td>' + infant_selling +
            '</td></tr><tr><td><strong>Tax</strong></td><td>' + adult_tax + '</td><td>' + child_tax +
            '</td><td>' + infant_tax + '</td></tr><tr><td><strong>Total Fare</strong></td><td>' +
            total_adult_fare + '</td><td>' + total_child_fare + '</td><td>' + total_infant_fare +
            '</td></tr></tbody></table>';
        $('.fare_data').html(str);
        $('#dynamic_text').text('Fare Details');
        $("#action").modal('show');
    });

    $(".flight_details").click(function() {
        var fsid = $(this).data('fsid');
        $.ajax({
            url: "<?= base_url();?>index.php/flight_crs/get_flight_details/" + fsid,
            success: function(result) {

                var res = JSON.parse(result);
                //  alert(result)
                if (res.length > 0) {

                    var flight_data = '';
                    var flight_data =
                        '<table class="table table-bordered"><thead><tr><th>#SL</th><th>Origin</th><th>Destination</th><th>Deparature From Date</th><th>Deparature To Date</th><th>Flight Num</th><th>Carrier code</th><th>Airline Name</th><th>Class</th><th>Trip Type</th><th>Baggage/Checkin Baggage</th><th>Meals</th><th>Extra</th></tr></thead><tbody>';
                    for (var i = 0; i < res.length; i++) {
                        flight_data += '<tr><td>' + parseInt(i + 1) + '</td><td>' + res[i][
                                'origin'
                            ] + '</td><td>' + res[i]['destination'] + '</td><td>' + res[i][
                                'departure_from_date'
                            ] + ' ' + res[i]['departure_time'] + '</td><td>' + res[i][
                                'departure_to_date'
                            ] + ' ' + res[i]['arrival_time'] + '</td><td>' + res[i][
                                'flight_num'
                            ] + '</td><td>' + res[i]['airline_name'] + '</td><td>' + res[i][
                                'airline_name'
                            ] + '</td><td>' + res[i]['class_type'] + '</td><td>' + ((res[i][
                                'trip_type'
                            ] == 0) ? "Onward" : "Return") + '</td><td>' + res[i][
                                'baggage'
                            ] + ' + ' + res[i]['checkin_baggage'] + '</td><td>' +
                            res[i]['meals'] + '</td><td>' + res[i]['extra'] + '</td></tr>';
                    }
                    flight_data += '</tbody></table>';
                }
                $('.fare_data').html(flight_data);
                $('#dynamic_text').text('Flight Details');
                $("#action").modal('show');

            }
        });
        // var str='<table class="table table-bordered"><thead><tr><th></th><th>Origin</th><th>Destination</th><th>Flight Num</th><th>Carrier code</th><th>Airline Name</th></tr></thead><tbody>";
        // </tbody></table>';
        // $('.dyn_data').html(str);
        //$("#action").modal('show'); 
        //$(".dyn_title").text("Flight Details");
    });

    /*$('#tab_flight_list').DataTable({
		  "searching": true,
		  "paging" : false
	});*/

    $(".getAiportlist").autocomplete({
        source: app_base_url + "index.php/flight_crs/get_flight_suggestions",
        minLength: 2, //search after two characters
        autoFocus: true, // first item will automatically be focused
        select: function(event, ui) {
            //var inputs = $(this).closest('form').find(':input:visible');
            //inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });

});
</script>