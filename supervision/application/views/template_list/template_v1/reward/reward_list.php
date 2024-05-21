<?php
$currency_code = get_application_transaction_currency_preference();
?>
<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent">
    <div class="panel panel-default"><!-- PANEL WRAP START -->
        <div class="panel-heading"><!-- PANEL HEAD START -->
            <div class="panel-title">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
                    <li role="presentation" class="<?php echo 'active'; ?>"><a
                            href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
                            <i class="fa fa-picture-o"></i> <?php echo 'Reward Point List'; ?> </a>
                    </li>
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
                </ul>
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="panel-body"><!-- PANEL BODY START -->
            <div class="tab-content">
                <div role="tabpanel" class="clearfix tab-pane active"
                     id="tableList">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <form action="<?php echo base_url(); ?>index.php/reward/reward_points" method="post" class='form form-horizontal validate-form'>
                                <table class="data-table-column-filter table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Module</th>
                                            <th>Maximum Spending above</th>
                                            <th>Reward Point(s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Hotel</td>
                                            <td><input type="text" name="hotel_minimum_spending" value="<?php echo $table_data['hotel_minimum_spending'] ?>"/></td>
                                            <td><input type="text" name="hotel_reward_point" value="<?php echo $table_data['hotel_reward_point'] ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td>Flight</td>
                                            <td><input type="text" name="flight_minimum_spending" value="<?php echo $table_data['flight_minimum_spending'] ?>"/></td>
                                            <td><input type="text" name="flight_reward_point" value="<?php echo $table_data['flight_reward_point'] ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td>Car</td>
                                            <td><input type="text" name="car_minimum_spending" value="<?php echo $table_data['car_minimum_spending'] ?>"/></td>
                                            <td><input type="text" name="car_reward_point" value="<?php echo $table_data['car_reward_point'] ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td>Package</td>
                                            <td><input type="text" name="vacation_minimum_spending" value="<?php echo $table_data['vacation_minimum_spending'] ?>"/></td>
                                            <td><input type="text" name="vacation_reward_point" value="<?php echo $table_data['vacation_reward_point'] ?>" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class='form-group'>
                                    <div class='col-sm-6 col-md-5 col-md-offset-2'>
                                    <label class='control-label col-xs-12 padt0'  for='validation_current'>1 Reward Point =<br>
                                        
                                    </label>
                                    <p class="text-right">( user will get discount based on number of rewards they get. )</p>
                                    </div>
                                    <div class='col-sm-3 controls'>
                                      <div class="controls">
                                        <input type="text" name="get_reward_point" value="<?php echo $table_data['get_reward_point'] ?>" id="get_reward_point" class='form-control ma_wd'>
                                        
                                        <strong class='controls'> CAD</strong>
                                      </div>
                                    </div>
                                </div>
                                <div class='form-actions' style='margin-bottom: 0'>
                                    <div class='row'>
                                            <div class='col-sm-9 col-sm-offset-3'>
                                                    <button class='btn btn-primary' type='submit'>Update</button>
                                            </div>
                                    </div>
				</div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PANEL BODY END --></div>
    <!-- PANEL WRAP END --></div>
<!-- HTML END -->
<?php

function get_table($table_data = '') {
    $table = '';
    //$pagination = $GLOBALS['CI']->pagination->create_links();
    //$table .= $pagination;
    $table .= '
   <div class="table-responsive">
   <table class="data-table-column-filter table table-bordered table-striped" style="margin-bottom:0;" id="hotel_style_list">';
    $table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> ' . get_app_message('AL006') . '</th>
   <th>'.get_app_message('AL00339').'</th>
   <th>'.get_app_message('AL0019').'</th>
   <th>'.get_app_message('AL0012').'</th>
   </tr></thead><tbody>';

    if (valid_array($table_data) == true) {
        $segment_3 = $GLOBALS['CI']->uri->segment(3);
        $current_record = (empty($segment_3) ? 0 : $segment_3);
        foreach ($table_data as $k => $v) {
            $table .= '<tr>
			<td>' . ( ++$current_record) . '</td>
                        <td>' . $v['hotel_style_name'] . '</td>
			<td>'.get_status_toggle_button($v['hotel_style_status'], $v['hotel_style_id']).'</td>
			<td>'.get_edit_button($v['hotel_style_id']).'</td>
</tr>';
        }
    } //else {
        //$table .= '<tr><td colspan="8">' . get_app_message('AL005') . '</td></tr>';
    //}
    $table .= '</tbody>';
        //footer Search Section        
        $table .= '<tfoot><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> ' . get_app_message('AL006') . '</th>
   <th>'.get_app_message('AL00339').'</th>
   <th>'.get_app_message('AL0019').'</th>
   <th>'.get_app_message('AL0012').'</th>
   </tr></tfoot>'; 
        $table .='</table></div>';
    return $table;
}
function get_banner_image($banner_image) {
	if (empty ( $banner_image ) == false && file_exists ( $GLOBALS ['CI']->template->domain_image_full_path ( $banner_image ) )) {
		return '<img src="' . $GLOBALS ['CI']->template->domain_images ( $banner_image ) . '" height="100px" width="100px" class="img-thumbnail">';
	}
}
function get_status_label($status) {
    if (intval($status) == ACTIVE) {
        return '<span class="label label-success"><i class="fa fa-circle-o"></i> ' . get_enum_list('status', ACTIVE) . '</span>
	<a role="button" href="" class="hide">' . get_app_message('AL0021') . '</a>';
    } else {
        return '<span class="label label-danger"><i class="fa fa-circle-o"></i> ' . get_enum_list('status', INACTIVE) . '</span>
		<a role="button" href="" class="hide">' . get_app_message('AL0020') . '</a>';
    }
}

function get_status_toggle_button($status, $user_id) {
    $status_options = get_enum_list('status');
    return '<select class="toggle-template-status" data-user-id="' . $user_id . '">' . generate_options($status_options, array($status)) . '</select>';    
}

function get_edit_button($id) {
    return '<a role="button" href="' . base_url() . 'index.php/hotel_crs/hotel_style?' . $_SERVER['QUERY_STRING'] . '&	eid=' . $id . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		' . get_app_message('AL0022') . '</a>
		';
    /* <a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
      <span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a> */
}
?>
<script type="text/javascript">
        function activate(that) { window.location.href = that; }
        $(document).ready(function() {
            var oTable = $('#hotel_style_list').dataTable();
            oTable.fnDestroy();
//            $('#b2c_users').dataTable( {
//                "info":     true,
//                "aoColumnDefs": [{ 'bSortable': false, 'aTargets': [ 1 ] }]
//            } );
        } );
    </script>

<script>
    $(document).ready(function () {
        $('.toggle-template-status').on('change', function (e) {
            e.preventDefault();
            var _user_status = this.value;
            var _opp_url = app_base_url + 'index.php/hotel_crs/';
            if (parseInt(_user_status) == 1) {
                _opp_url = _opp_url + 'activate_hotel_style/';
            } else {
                _opp_url = _opp_url + 'deactivate_hotel_style/';
            }
            _opp_url = _opp_url + $(this).data('user-id');
            toastr.info('Please Wait!!!');
            $.get(_opp_url, function () {
                toastr.info('Updated Successfully!!!');
            });
        });
    });
</script>
