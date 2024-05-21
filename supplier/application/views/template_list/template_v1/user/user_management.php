<?php if (form_visible_operation()) {
	$tab1 = " active ";
	$tab2 = "";
} else {
	$tab2 = " active ";
	$tab1 = "";
}

// debug($_GET['q']);
// die;
$user_type=@$_GET['q'];

$segment_2 = $GLOBALS['CI']->uri->segment(2);

if(($segment_2=='b2c_user' || $_GET['q']>1) && !isset($_GET['eid']))
{
	$tab2 = " active ";
	$tab1 = "";
}
// debug($segment_2);
// die;
$_datepicker = array(array('created_datetime_from', PAST_DATE), array('created_datetime_to', PAST_DATE));
$this->current_page->set_datepicker($_datepicker);
if(!empty($search_params)){
	if (is_array($search_params)) {
	extract($search_params);
	}	
}

if($domain_admin_exists == true && (isset($eid) == false || empty($eid) == true)) {
	//if domain admin exists and not in update mode, then hide add form
	$domain_admin_exists = true;
} else {
	$domain_admin_exists = false;
}
// if (is_array($search_params)) {
// 	extract($search_params);
// }
?>
<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent">
<div class="panel panel-default"><!-- PANEL WRAP START -->
<div class="panel-heading"><!-- PANEL HEAD START -->
<div class="panel-title">
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
<?php if($domain_admin_exists == false) { ?>
	<li role="presentation" class="<?php echo $tab1; ?>"><a
		id="fromListHead" href="#fromList" aria-controls="home" role="tab"
		data-toggle="tab"> <i class="fa fa-edit"></i> <?php echo get_app_message('AL0014');?>
	</a></li>
	<?php  } ?>
	<li role="presentation" class="<?php echo $tab2; ?>"><a
		href="#tableList" aria-controls="profile" role="tab" data-toggle="tab">
	<i class="fa fa-users"></i> <?php echo get_app_message('AL0015');?> </a>
	</li>
	<!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
</ul>
</div>
</div>
<!-- PANEL HEAD START -->
<div class="panel-body"><!-- PANEL BODY START -->
<div class="tab-content"><?php if($domain_admin_exists == false) { ?>
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab1; ?>" id="fromList">
<div class="panel-body"><?php 

// debug($form_data);
// die;
/************************ GENERATE CURRENT PAGE FORM ************************/
if (isset($eid) == false || empty($eid) == true) {
	
	/*** GENERATE ADD PAGE FORM ***/
	$form_data['country_code'] = (isset($form_data['country_code']) == false ? INDIA_CODE : $form_data['country_code']);
	$form_data['status'] = ACTIVE;
	
	echo $this->current_page->generate_form('user', $form_data);
} else {

	
	echo $this->current_page->generate_form('user_edit', $form_data);
}
/************************ GENERATE UPDATE PAGE FORM ************************/
?></div>
</div>
<?php } ?>
<div role="tabpanel" class="clearfix tab-pane <?php echo $tab2; ?>" id="tableList">
<!--/************************ GENERATE Filter Form ************************/-->
<h4>Search Panel</h4>

<hr>
<form method="GET" autocomplete="off" id="search_filter_form">
	<input type="hidden" name="user_status" value="<?=@$user_status?>" >
	<div class="clearfix form-group">
		<div class="col-xs-4">
			<label>User ID</label>
			<input type="text" placeholder="User ID" value="<?=@$uuid?>" name="uuid" class="search_filter form-control">
		</div>
		<div class="col-xs-4">
			<label>Email</label>
			<input type="text" placeholder="Email" value="<?=provab_decrypt(@$email)?>" name="email" class="search_filter form-control">
		</div>
		<div class="col-xs-4">
			<label>Phone</label>
			<input type="text" placeholder="Phone Number" value="<?=@$phone?>" name="phone" class="search_filter numeric form-control">
		</div>
		<div class="col-xs-4">
			<label>Member Since</label>
			<input type="text" placeholder="Registration Date" readonly value="<?=@$created_datetime_from?>" id="created_datetime_from" name="created_datetime_from" class="search_filter form-control">
		</div>
	</div>
	<div class="col-sm-12 well well-sm">
		<button class="btn btn-primary" type="submit">Search</button> 
		<button class="btn btn-warning" type="reset">Reset</button>
		<a href="<?php echo base_url(); ?>index.php/user/b2c_user/?filter=user_type&q=4&user_status=<?php echo @$user_status;?>" id="clear-filter" class="btn btn-primary">ClearFilter</a>
	</div>
</form>
<div class="clearfix"></div>
<!--/************************ GENERATE Filter Form ************************/-->
<div class="panel-body"><?php
/************************ GENERATE CURRENT PAGE TABLE ************************/
echo get_table(@$table_data,$s_usr,$user_type);
/************************ GENERATE CURRENT PAGE TABLE ************************/
?></div>
</div>
</div>
</div>
<!-- PANEL BODY END --></div>
<!-- PANEL WRAP END --></div>
<!-- HTML END -->
<?php
function get_table($table_data='',$s_usr,$user_type)
{

	// debug($s_usr);
	$table = '';
	$pagination = $GLOBALS['CI']->pagination->create_links();
	$table .= $pagination;
	$table .= '
   <div class="table-responsive">
   <table class="table table-hover table-striped table-bordered table-condensed">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> '.get_app_message('AL006').'</th>
   <th>Login Status</th>
   <th>'.get_app_message('AL007').'</th>
   <th>'.get_app_message('AL008').'</th>
   ';
   if($user_type != SUB_ADMIN){

	if (is_active_airline_module()) {
		$table .= '<th>Flight</th>';
	}
	if (is_active_hotel_module()) {
		$table .= '<th>Hotel</th>';
	}
	if (is_active_bus_module()) {
		$table .= '<th>Bus</th>';
	}
	if (is_active_transferv1_module()) {
		$table .= '<th>Transfers</th>';
	}
	if (is_active_sightseeing_module()) {
		$table .= '<th>Sightseeing</th>';
	}
}
	$table .= '<th>'.get_app_message('AL0019').'</th>
   <th>'.get_app_message('AL0012').'</th> </tr></thead><tbody>';
   

  

	if (valid_array($table_data) == true) {
		$segment_3 = $GLOBALS['CI']->uri->segment(3);
		$current_record = (empty($segment_3) ? 0 : $segment_3);
		foreach ($table_data as $k => $v) {
			//debug($v);die;
			$rep_url = base_url().'index.php/report/';
			$dep_req = '';
			if (isset($v['dep_req']) == true && isset($v['dep_req']['pending']) == true) {
				$dep_req = intval($v['dep_req']['pending']['count']);
			} else {
				$dep_req = 0;
			}
			$booking_summ = '';
if($user_type != SUB_ADMIN){

			if (is_active_airline_module()) {
				$booking_summ .= '<td> <a target="_blank" href="'.$rep_url.'b2c_flight_report?created_by_id='.$v['user_id'].'">view</a></td>';
			}

			
			// if (is_active_hotel_module()) {
			// 	$booking_summ .= '<td>'.intval(@$v['booking_summ']['hotel']['BOOKING_CONFIRMED']['count']).'  <a target="_blank" href="'.$rep_url.'b2b_hotel_report?created_by_id='.$v['user_id'].'">view</a></td>';
			// }

			if (is_active_hotel_module()) {
				$booking_summ .= '<td> <a target="_blank" href="'.$rep_url.'b2c_hotel_report?created_by_id='.$v['user_id'].'">view</a></td>';
			}
			
			// if (is_active_bus_module()) {
			// 	$booking_summ .= '<td>'.intval(@$v['booking_summ']['bus']['BOOKING_CONFIRMED']['count']).'  <a target="_blank" href="'.$rep_url.'b2b_bus_report?created_by_id='.$v['user_id'].'">view</a></td>';
			// }

			if (is_active_bus_module()) {
				$booking_summ .= '<td><a target="_blank" href="'.$rep_url.'b2c_bus_report?created_by_id='.$v['user_id'].'">view</a></td>';
			}

			// if (is_active_transferv1_module()) {
			// 	$booking_summ .= '<td>'.intval(@$v['booking_summ']['transfer']['BOOKING_CONFIRMED']['count']).'  <a target="_blank" href="'.$rep_url.'b2b_transfers_report?created_by_id='.$v['user_id'].'">view</a></td>';
			// }

			if (is_active_transferv1_module()) {
				$booking_summ .= '<td> <a target="_blank" href="'.$rep_url.'b2c_transfers_report?created_by_id='.$v['user_id'].'">view</a></td>';
			}

			// if (is_active_sightseeing_module()) {
			// 	$booking_summ .= '<td>'.intval(@$v['booking_summ']['sightseeing']['BOOKING_CONFIRMED']['count']).'  <a target="_blank" href="'.$rep_url.'b2b_sightseeing_report?created_by_id='.$v['user_id'].'">view</a></td>';
			// }

			if (is_active_sightseeing_module()) {
				$booking_summ .= '<td><a target="_blank" href="'.$rep_url.'b2c_activities_report?created_by_id='.$v['user_id'].'">view</a></td>';
			}
		}

			$last_login = 'Last Login : '.last_login($v['last_login']);
			$login_status = login_status($v['logout_date_time']);
			$table .= '<tr>
			<td>'.(++$current_record).'</td>
			<td class="hand-cursor" title="'.$last_login.'">'.$login_status.'</td>
			<td>'.get_enum_list('title', $v['title']).' '.$v['first_name'].' '.$v['last_name'].'</td>
			<td>'.$v['phone'].'-'.provab_decrypt($v['email']).'</td>
			'.$booking_summ.'
			<td>'.get_status_toggle_button($v['status'], $v['user_id'], $v['uuid']).'</td>';
if($s_usr=='subadmin')
			$table.='<td>'.get_edit_button($v['user_id']).get_priv_button($v['user_id']).'</td></tr>';
		else
			$table.='<td>'.get_edit_button($v['user_id']).'</td></tr>';
		}
	} else {
		$table .= '<tr><td colspan="8">'.get_app_message('AL005').'</td></tr>';
	}
	$table .= '</tbody></table></div>';
	return $table;
}

function get_status_label($status)
{
	if (intval($status) == ACTIVE) {
		return '<span class="label label-success"><i class="fa fa-circle-o"></i> '.get_enum_list('status', ACTIVE).'</span>
	<a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
	} else {
		return '<span class="label label-danger"><i class="fa fa-circle-o"></i> '.get_enum_list('status', INACTIVE).'</span>
		<a role="button" href="" class="hide">'.get_app_message('AL0020').'</a>';
	}
}
function get_priv_button($id)
{
	return '<a role="button" href="'.base_url().'index.php/user/privilege_management?'.$_SERVER['QUERY_STRING'].'&	eid='.$id.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL00333').'</a>';
	/*<a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
		<span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a>*/
}

function get_status_toggle_button($status, $user_id, $uuid)
{
	// $status_options = get_enum_list('status');
	// //debug($status);die;
	// return '<select class="toggle-user-status" data-user-id="'.$user_id.'" data-uuid="'.$uuid.'">'.generate_options($status_options, array($status)).'</select>';
	/*if (intval($status) == INACTIVE) {
		return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
	} else {
		return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
	}*/
	//die;
	// return $status;
	$status_options = get_enum_list('status');
	// debug($status_options);

	$select = '<select autocomplete="off" class="toggle-user-status" data-user-id="'.$user_id.'" data-uuid="'.$uuid.'">';
	foreach ($status_options as $key => $value) {
		$active=($key==$status)?'selected':'';
		
	$select.='<option value="'.$key.'" '.$active.'>'.$value.'</option>';
	}
	$select.='</select>';
	return $select;
}

function get_edit_button($id)
{
	return '<a role="button" href="'.base_url().'index.php/user/user_management?'.$_SERVER['QUERY_STRING'].'&	eid='.$id.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		'.get_app_message('AL0022').'</a>
		';
	/*<a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
		<span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a>*/
}
?>
<script>
$(document).ready(function() {
	$('.toggle-user-status').on('change', function(e) {
		e.preventDefault();
		var _user_status = this.value;
		var _opp_url = app_base_url+'index.php/user/';
		if (parseInt(_user_status) == 1) {
			_opp_url = _opp_url+'activate_account/';
		} else {
			_opp_url = _opp_url+'deactivate_account/';
		}
		_opp_url = _opp_url+$(this).data('user-id')+'/'+$(this).data('uuid');
		toastr.info('Please Wait!!!');
		$.get(_opp_url, function() {
			toastr.info('Updated Successfully!!!');
			location.reload();
		});
	});
});
</script>


<script>
$(document).ready(function()
{
	$('#phone').attr("min","0");
	$('#phone').keypress(function () {
		// alert('ererer');
	var maxLength = $('#phone').val().length;
// alert(maxLength);
	if(maxLength>=10)

{
	alert('Phone number should have max 10 digits');
	return false;
}    

});
});

</script>

