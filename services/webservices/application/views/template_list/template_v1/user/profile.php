<?php
$datepicker = array(array('date_of_birth', PAST_DATE));
$GLOBALS['CI']->current_page->set_datepicker($datepicker);
?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			User Profile
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<!-- Profile Image -->
				<div class="panel panel-default">
					<div class="panel-body">
						<img alt="User profile picture" src="<?=$GLOBALS['CI']->template->template_images(get_profile_image($GLOBALS['CI']->entity_image));?>" class="profile-user-img img-responsive img-circle">
						<h3 class="text-center"><i class="fa fa-circle text-success"></i> <?=$this->entity_name?></h3>
						<p class="text-muted text-center"><?php echo (empty($this->entity_date_of_birth) == false ? app_friendly_date($this->entity_date_of_birth) : 'Date Of Birth');?></p>
						<ul class="list-group list-group-unbordered">
							<li class="list-group-item">
								<b>User ID</b> <a class="pull-right"><?=$this->entity_uuid?></a>
							</li>
							<li class="list-group-item">
								<b>Email</b> <a class="pull-right"><?=$this->entity_email?></a><br><br>								
							</li>
							<li class="list-group-item">
								<b>Points</b> <a class="pull-right">13,287</a>
							</li>
						</ul>
						<a class="btn btn-default btn-block" href="#">Since :<b><?=app_friendly_date($this->entity_created_datetime)?></b></a>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
				<!-- About Me Box -->
				<div class="panel panel-default">
					<div class="panel-heading with-border">
						<h3 class="panel-title">About Me</h3>
					</div>
					<!-- /.box-header -->
					<div class="panel-body">
						<strong><i class="fa fa-pencil margin-r-5"></i> My Bookings</strong>
						<p>
							<ul class="list-group">
								<?php
								$active_domain_modules = $GLOBALS['CI']->active_domain_modules;
								$master_module_list = $GLOBALS['CI']->config->item('master_module_list');
								foreach ($master_module_list as $k => $v) {
									if (in_array($k, $active_domain_modules)) {
									?>
									<li class="list-group-item <?=get_arrangement_color($k)?>"><a href="<?php echo base_url()?>report/<?=strtolower($v)?>" class="white-text">
										<i class="<?=get_arrangement_icon(module_name_to_id($v))?>"></i> <span class="pull-right"><?=ucfirst($v)?> Booking</span></a></li>
									<?php
									}
								}
								?>
							</ul>
						</p>
						<hr>
						<strong><i class="fa fa-phone margin-r-5"></i>Phone</strong>
						<p class="">
							<?=(empty($this->entity_phone) == false ? $this->entity_phone : 'Update Now')?>
						</p>
						<hr>
						<strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>
						<?=(empty($this->entity_address) == false ? $this->entity_address : 'Update Now')?>
						<hr>
						<strong><i class="fa fa-file-text-o margin-r-5"></i> Language Preference</strong>
						<p><?=strtoupper($this->entity_language_preference)?></p>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
			<div class="col-md-9">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#activity" aria-expanded="true">Activity</a></li>
						<li class=""><a data-toggle="tab" href="#timeline" aria-expanded="false">Timeline</a></li>
						<li class=""><a data-toggle="tab" href="#settings" aria-expanded="false">Settings</a></li>
					</ul>
					<div class="tab-content">
						<div id="activity" class="tab-pane active">
							<!-- Post -->
							<div class="post">
								<div class="user-block">
									<img alt="user image" src="../../dist/img/user1-128x128.jpg" class="img-circle img-bordered-sm">
									<span class="username">
									<a href="#">Jonathan Burke Jr.</a>
									<a class="pull-right btn-box-tool" href="#"><i class="fa fa-times"></i></a>
									</span>
									<span class="description">Shared publicly - 7:30 PM today</span>
								</div>
								<!-- /.user-block -->
								<p>
									Lorem ipsum represents a long-held tradition for designers,
									typographers and the like. Some people hate it and argue for
									its demise, but others ignore the hate as they create awesome
									tools to help create filler text for everyone from bacon lovers
									to Charlie Sheen fans.
								</p>
							</div>
							<!-- /.post -->
							<!-- Post -->
							<div class="post clearfix">
								<div class="user-block">
									<img alt="user image" src="../../dist/img/user7-128x128.jpg" class="img-circle img-bordered-sm">
									<span class="username">
									<a href="#">Sarah Ross</a>
									<a class="pull-right btn-box-tool" href="#"><i class="fa fa-times"></i></a>
									</span>
									<span class="description">Sent you a message - 3 days ago</span>
								</div>
								<!-- /.user-block -->
								<p>
									Lorem ipsum represents a long-held tradition for designers,
									typographers and the like. Some people hate it and argue for
									its demise, but others ignore the hate as they create awesome
									tools to help create filler text for everyone from bacon lovers
									to Charlie Sheen fans.
								</p>
							</div>
							<!-- /.post -->
						</div>
						<!-- /.tab-pane -->
						<div id="timeline" class="tab-pane">
							<!-- The timeline -->
							 <ul class="timeline-wrapper timeline" id="timeline-list">
							 </ul>
							 <div id="event_bottom_chain">
								<div style="" class="data-utility-loader text-center">
									<span><span/>Please Wait <img class="img-responsive center-block" src="/proapp_ng/extras/system/template_list/template_v1/images/tiny_loader_v1.gif">
								</div>
							</div>
						</div>
						<?php
						$adult_enum = $child_enum = get_enum_list('title');
						$adult_title_options = generate_options($adult_enum, array($title), true);
						?>
						<!-- /.tab-pane -->
						<div id="settings" class="tab-pane">
							<hr>
							<form class="form-horizontal" autocomplete="off" method="post">
								<div class="form-group">
									<label class="col-sm-2 control-label">Name</label>
									<div class="col-sm-2">
										<select required class="form-control" name="title">
										<?=$adult_title_options?>
										</select>
									</div>
									<div class="col-sm-4">
										<input required type="text" value="<?=$first_name?>" name="first_name" placeholder="First Name" id="" class="form-control">
									</div>
									<div class="col-sm-4">
										<input required type="text" value="<?=$last_name?>" name="last_name" placeholder="Last Name" id="" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Date Of Birth</label>
									<div class="col-sm-2">
										<input required type="text" value="<?=$date_of_birth?>" id="date_of_birth" name="date_of_birth" placeholder="DOB" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="inputName">Phone</label>
									<div class="col-sm-2">
										<select required class="form-control" name="country_code">
										<?=generate_options($country_code, array(INDIA_CODE))?>
										</select>
									</div>
									<div class="col-sm-8">
										<input required type="text" value="<?=$phone?>" name="phone" placeholder="Mobile" id="" class="form-control numeric">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Address</label>
									<div class="col-sm-10">
										<textarea required placeholder="Address" name="address" id="" class="form-control"><?=$address?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label" for="">Signature</label>
									<div class="col-sm-10">
										<textarea placeholder="Signature" name="signature" id="" class="form-control"><?=$signature?></textarea>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button class="btn btn-primary" type="submit">Submit</button>
									</div>
								</div>
							</form>
						</div>
						<!-- /.tab-pane -->
					</div>
					<!-- /.tab-content -->
				</div>
				<!-- /.nav-tabs-custom -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<script>
$(document).ready(function() {
	function load_timeline(_event_start, event_limit)
	{
		if ($('#timeline').is(':visible') == false) {
			return false;
		}
		load_event_bottom_chain = false;
		$.get('<?php echo base_url().'index.php/utilities/timeline_rack?'?>oe_start='+_event_start+'&oe_limit='+event_limit, function(response) {
			if (response.status == false) {
				load_event_bottom_chain = false;
				$('#event_bottom_chain').hide();
				$('#timeline-list').append('<li><i class="fa fa-clock-o bg-gray"></i></li>');
			} else {
				$('#timeline-list').append(response.oe_list);
				load_event_bottom_chain = true;
				event_start = (event_start+event_limit);
				lazy_loader();
				adjust_time_label();
			}
		});
	}
	function adjust_time_label()
	{
		var time_head_list = {};
		var _cur_label_id = '';
		var _pre_label_id = '';
		var event_stamp = 'rt_list'+(new Date()).getTime();
		$('.time-label').each(function(k, v) {
			cur_ele = $(this);
			_cur_label_id = this.id;
			if (_cur_label_id == _pre_label_id) {
				$(this).fadeOut(3000).addClass(event_stamp);
			} else {
				_pre_label_id = _cur_label_id;
			}
		});
		setTimeout(function() {
			$('.'+event_stamp).remove();
			lazy_loader();
		}, 3000);
	}
	//Load timeline events
	$(window).scroll(function() {
		lazy_loader();
	});

	function lazy_loader()
	{
		if (isVisibleOnViewPort('#event_bottom_chain') == true && load_event_bottom_chain == true) {
			load_timeline(event_start, event_limit);
		}
	}
	function isVisibleOnViewPort(elem)
	{
		var $elem = $(elem);
		var $window = $(window);

		var docViewTop = $window.scrollTop();
		var docViewBottom = docViewTop + $window.height();

		var elemTop = $elem.offset().top;
		var elemBottom = elemTop + $elem.height();

		return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
	}

	//
	var event_start = 0;
	var event_limit = 20;
	var load_event_bottom_chain = true;
	lazy_loader(event_start, event_limit);


	//setInterval(function() {
		//kill all the queued events and send new request
	//os_event_list();
	//}, 10000);

	
	//function os_event_list()
	//{
		//var _latest_event_id = parseInt($('.event-origin:first').data('event-id'));
		//_latest_event_id = 50;
		//if (_latest_event_id > 0) {
			//$.get('<?=base_url()?>utilities/latest_timeline_events?last_event_id='+_latest_event_id, function(response) {
				//if (response.status) {
					//$('#timeline-list').prepend(response.oa_list);
					//adjust_time_label();
				//}
			//});
		//}
	//}

	var os_event_list = function() {
		var _latest_event_id = parseInt($('.event-origin:first').data('event-id'));
		//_latest_event_id = 50;
		if (_latest_event_id > 0) {
			$.ajax({
				url: '<?=base_url()?>utilities/latest_timeline_events?last_event_id='+_latest_event_id,
				success: function(response) {
					if (response.status) {
						$('#timeline-list').prepend(response.oa_list);
						adjust_time_label();
					}
				},

				complete: function(){setTimeout(os_event_list, 500);}
			});
		}
	};
	var interval = setTimeout(os_event_list, 5000);
});
</script>
<!-- Timeline  -->
<style>
.timeline::before {
    background: #ddd none repeat scroll 0 0;
    border-radius: 2px;
    bottom: 0;
    content: "";
    left: 31px;
    margin: 0;
    position: absolute;
    top: 0;
    width: 4px;
}
.timeline {
    list-style: outside none none;
    margin: 0 0 30px;
    padding: 0;
    position: relative;
}
.timeline > li::before, .timeline > li::after {
    content: " ";
    display: table;
}
.timeline > li::after {
    clear: both;
}
.timeline > li::before, .timeline > li::after {
    content: " ";
    display: table;
}
.timeline > li {
    margin-bottom: 15px;
    margin-right: 10px;
    position: relative;
}
.timeline > li.time-label > span {
    background-color: #fff;
    border-radius: 4px;
    display: inline-block;
    font-weight: 600;
    padding: 5px;
}
.timeline > li > .fa, .timeline > li > .glyphicon, .timeline > li > .ion {
    background: #d2d6de none repeat scroll 0 0;
    border-radius: 50%;
    color: #666;
    font-size: 15px;
    height: 30px;
    left: 18px;
    line-height: 30px;
    position: absolute;
    text-align: center;
    top: 0;
    width: 30px;
}
.timeline > li > .timeline-item {
    background: #fff none repeat scroll 0 0;
    border-radius: 3px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    color: #444;
    margin-left: 60px;
    margin-right: 15px;
    margin-top: 0;
    padding: 0;
    position: relative;
}
.timeline > li > .timeline-item > .time {
    color: #999;
    float: right;
    font-size: 12px;
    padding: 10px;
}
.timeline > li > .timeline-item > .timeline-header {
    border-bottom: 1px solid #f4f4f4;
    color: #555;
    font-size: 16px;
    line-height: 1.1;
    margin: 0;
    padding: 10px;
}
.timeline > li > .timeline-item > .timeline-body, .timeline > li > .timeline-item > .timeline-footer {
    padding: 10px;
}
</style>
<!-- Styling -->
<style>
.profile-user-img {
    border: 3px solid #d2d6de;
    margin: 0 auto;
    padding: 3px;
    width: 100px;
}
.profile-username {
    font-size: 21px;
    margin-top: 5px;
}

.list-group-unbordered > .list-group-item {
    border-left: 0 none;
    border-radius: 0;
    border-right: 0 none;
    padding-left: 0;
    padding-right: 0;
}

.content {
    margin-left: auto;
    margin-right: auto;
    min-height: 250px;
    padding: 30px;
}
.content-wrapper {
	background-color: #ecf0f5;
}
.content-header {
    padding: 15px 15px 0;
    position: relative;
}
.content-header > h1 {
    font-size: 24px;
    margin: 0;
}
.nav-tabs-custom {
    background: #fff none repeat scroll 0 0;
    border-radius: 3px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}
.nav-tabs-custom > .nav-tabs {
    border-bottom-color: #f4f4f4;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    margin: 0;
}
.nav-tabs-custom > .nav-tabs > li {
    border-top: 3px solid transparent;
    margin-bottom: -2px;
    margin-right: 5px;
}
.nav-tabs-custom > .nav-tabs > li.active {
    border-top-color: #3c8dbc;
}
.nav-tabs-custom > .tab-content {
    background: #fff none repeat scroll 0 0;
    border-bottom-left-radius: 3px;
    border-bottom-right-radius: 3px;
    padding: 10px;
}
.btn.btn-flat {
    border-radius: 0;
    border-width: 1px;
    box-shadow: none;
}
</style>