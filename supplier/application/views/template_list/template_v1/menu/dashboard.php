<?php
$active_domain_modules = $this->active_domain_modules;
$tiny_loader = $GLOBALS['CI']->template->template_images('tiny_loader_v1.gif');
$tiny_loader_img = '<img src="'.$tiny_loader.'" class="loader-img" alt="Loading">';
$booking_summary = array();
// $car_supplier =is_active_car_supplier();
// $car_driver = is_active_car_driver();
// $car_branch_user = is_active_car_branch_user(); 
?> 
<div class="container-fluid">
	<div class="row dsbrd">
			<h3>Reports</h3>

			<?php if(check_user_previlege('p120')) { ?>
			<div class="col-md-3 col-sm-6 col-xs-12">
		       <a href="<?=base_url()?>index.php/report/holiday" class=""> <!--supplier/enquiries-->
					<span class="info-box ">
						<span class="info-box-icon bg-yellow my-ib"><i class="fa fa-tree"></i></span>
						<div class="info-box-content ">
							<span class="info-box-text"> Tour CRS Bookings</span> 
							<?=$holiday_count?>
						</div>
						<!-- /.info-box-content -->
					</span>
					<!-- /.info-box -->
				</a>
			</div>
			<?php } ?>
				<?php if(check_user_previlege('p143')){ ?>
				<div class="col-md-3 col-sm-6 col-xs-12">
		       <a href="<?=base_url()?>index.php/report/b2c_hotelcrs_report" class=""> <!--supplier/enquiries-->
					<span class="info-box ">
						<span class="info-box-icon bg-yellow my-ib"><i class="<?= get_arrangement_icon(META_ACCOMODATION_COURSE) ?>"></i></span>
						<div class="info-box-content ">
							<span class="info-box-text"> Hotel CRS Bookings</span> 
							<?=$hotel_count?>
						</div>
						<!-- /.info-box-content -->
					</span>
					<!-- /.info-box -->
				</a>
			</div>
			<?php } ?>
			<?php if (check_user_previlege('p129')) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon transfers-l-bg"><i class="<?= get_arrangement_icon(META_TRANSFERV1_COURSE) ?>"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Transfer CRS Booking</span>
                        <span class="info-box-number <?= META_TRANSFERV1_COURSE ?>"><?= @$transfers_booking_count ?></span>
                        
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div>
        <?php } ?>
        	<?php if(check_user_previlege('p134')){ ?>
             <div class="col-md-3 col-sm-6 col-xs-12">
             <a href="<?=base_url()?>index.php/report/b2b_activities_report" class="">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-suitcase"></i></span>
                <div class="info-box-content">
                <span class="info-box-text">Activity CRS Bookings</span>
                 <?=$sightseeing_booking_count?>
                 
                  
                </div>
              </div>
               </a>
            </div>
                <?php } ?>
			<?php if (is_active_package_module() && check_supplier_previlege('2')) { ?>
       

            <?php } ?>

            <?php if (is_active_package_module() && check_supplier_previlege('4')) { ?>

            <!-- <div class="col-md-3 col-sm-6 col-xs-12">
				<a href="<?= base_url().'index.php/report/b2c_transfers_crs_report/'?>" class="">
					<span class="info-box">
						<span class="info-box-icon bg-lime"><i class="fa fa-car"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">B2C Transfers Bookings</span>
							<?=$transfers_booking_count?>
						</div>
					</span>
				</a>
			</div> -->
            <?php } ?>

            <?php if (is_active_package_module() && check_supplier_previlege('4')) { ?>

          
            <?php } ?>
						

			

			
			 
            <?php if (is_active_hotel_module() && check_supplier_previlege('3')) { ?>
            <!-- <div class="col-md-3 col-sm-6 col-xs-12">
	            <a href="<?=base_url()?>index.php/report/b2c_crs_hotel_report/" class="">
					<span class="info-box">
						<span class="info-box-icon bg-green"><i
							class="<?=get_arrangement_icon(META_ACCOMODATION_COURSE)?>"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Hotel Bookings</span>
							<?=$hotel_booking_count?>
						</div>
					</span>
				</a>
			</div> -->
            <?php } ?>
                          
		
		 
		
		 
		<!-- Insurance ends-->
	</div>
</div>
<div class="panel panel-default hide">
	<div class="panel-heading"><h3>Queues</h3></div>	
	<div class="panel-body"> 
		<?php if(is_active_airline_module ()){ ?>
<div class="col-md-3 col-sm-6 col-xs-12">
		<a  href="<?php echo base_url().'index.php/report/flight_hold_bookings/';?>">
<span class="info-box"><span class="info-box-icon bg-blue"><i
				class="<?=get_arrangement_icon(META_AIRLINE_COURSE)?>"></i> </span><div class="info-box-content"><span class="info-box-text"><span class="badge"><?=$flight_b_queue?></span> PENDING TKT ISSUE</span></div><!-- /.info-box-content --></span>

				</a>
	</div>			

		<?php } ?>
   
 
	</div>
</div>
<hr>  

 
<script>
$(document).ready(function() {
	var event_list = {};
	function enable_default_calendar_view()
	{
		load_calendar('');
	//	get_event_list();
	//	set_event_list();
		$('[data-toggle="tooltip"]').tooltip();
	}
	function reset_calendar()
	{
		$("#booking-calendar").fullCalendar('removeEvents');
		get_event_list();
		set_event_list();
	}
	//Reload Events
	setInterval(function(){
		reset_calendar();
		$('[data-toggle="tooltip"]').tooltip();
	}, <?php echo SCHEDULER_RELOAD_TIME_LIMIT; ?>);
	
	enable_default_calendar_view();
	//sets all the events
	function get_event_list()
	{
		set_booking_event_list();
	}
	//loads all the loaded events
	function set_event_list()
	{
		$("#booking-calendar").fullCalendar('addEventSource', event_list.booking_event_list);
		if ("booking_event_list" in event_list && event_list.booking_event_list.hasOwnProperty(0)) {
			//focus_date(event_list.booking_event_list[0]['start']);
		}
	}
	
	//getting the value of arrangment details
	function set_booking_event_list()
	{
		$.ajax({
			url:app_base_url+"index.php/ajax/booking_events",
			async:false,
			success:function(response){
				event_list.booking_event_list = response.data;
			}
		});
	}
	
	//load default calendar with scheduled query
	function load_calendar(event_list)
	{
		$('#booking-calendar').fullCalendar({
			header: {
				center: 'title'
			},
			//defaultDate: '2014-11-12', 
			editable: false,
			eventLimit: false, // allow "more" link when too many events
			events: event_list,
			eventRender: function(event, element) {
				element.attr('data-toggle', 'tooltip');
				element.attr('data-placement', 'bottom');
				element.attr('title', event.tip);
				element.attr('id', event.optid);
				element.find('.fc-time').attr('class',"hide"); 
				element.attr('class', event.add_class+' fc-day-grid-event fc-event fc-start fc-end');
				element.attr('href', event.href);
				element.attr('target', '_blank');
				element.css({'font-size':'10px', 'padding':'1px'});
				if (event.prepend_element) {
					element.prepend(event.prepend_element);
				}
			},
			eventDrop : function (event, delta) {
				event.end = event.end || event.start;
				if (event.start && event.end) {
					update_event_list(event.optid, event.start.format(), event.end.format());
					focus_date(event.start.format());
				} else {
					reset_calendar();
				}
			}
		});
	}
	function focus_date(date)
	{
		$('#booking-calendar').fullCalendar('gotoDate', date);
	}

	$(document).on('click', '.event-hand', function() {

	});
});
</script>
<style>
.fc-day-number {
    font-size: inherit;
    font-weight: inherit;
    padding-right: 10px;
}
</style>
<link defer href='<?php echo SYSTEM_RESOURCE_LIBRARY;?>/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link defer href='<?php echo SYSTEM_RESOURCE_LIBRARY;?>/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script defer src='<?php echo SYSTEM_RESOURCE_LIBRARY;?>/fullcalendar/lib/moment.min.js'></script>
<script defer src='<?php echo SYSTEM_RESOURCE_LIBRARY;?>/fullcalendar/fullcalendar.min.js'></script>

<!--  Get Driver Location Start -->
<?php if($car_driver==true){?>

<script type="text/javascript">
	var app_base_url = "<?php echo  base_url() ?>";
	var car_driver_id = "<?php echo $this->entity_user_id?>";
	var car_supplier_id = "<?php echo $this->entity_supplier_id?>";

	 function initialize() {
	 	var geocoder = new google.maps.Geocoder();
       if ("geolocation" in navigator){
			navigator.geolocation.getCurrentPosition(function(position){ 

				console.log(position);

				var currentLatitude = position.coords.latitude;
				var currentLongitude = position.coords.longitude;
				var latlng = new google.maps.LatLng(currentLatitude, currentLongitude);
           		geocoder.geocode({'latLng': latlng}, function(results, status) {
	                if(status == google.maps.GeocoderStatus.OK) {
	                    //alert(results[0]['formatted_address']);
	                    console.log(results[0]);
	                    var full_address = results[0]['formatted_address'];
	                   	
	                   	var locality_name='';
	                   	for (var i = results[0]['address_components'].length - 1; i >= 0; i--) {	                   		
	                   		for(var j=0;j<=results[0]['address_components'][i]['types'].length;j++){

	                   			if(results[0]['address_components'][i]['types'][j]=="sublocality_level_3"){
	                   				locality_name = results[0]['address_components'][i]['short_name'];
	                   			}else if(results[0]['address_components'][i]['types'][j]=="sublocality_level_2"){
	                   				locality_name = results[0]['address_components'][i]['short_name'];
	                   			}else if(results[0]['address_components'][i]['types'][j]=="sublocality_level_1"){
	                   				locality_name = results[0]['address_components'][i]['short_name'];

	                   			}else if(results[0]['address_components'][i]['types'][j]=="locality"){
	                   				locality_name = results[0]['address_components'][i]['short_name'];
	                   			}
	                   		}
	                   	}
	                    $.ajax({
	                    	url:app_base_url+"index.php/car_supplier/update_driver_location",
	                    	type:"POST",
	                    	data:{driver_id:car_driver_id,supplier_id:car_supplier_id,lat:currentLatitude,lon:currentLongitude,locality:locality_name,formatted_address:full_address},
	                    	success:function(res){
	                    		var html =''+ locality_name+'';
	                    		if(res==1){
	                    			alert('Your Current Location "'+html+' "  has been updated successfully,If this is not correct location please update your Location or Inform to admin');
	                    		}else if(res==2){
	                    			alert('Your Current Location "'+html+ '"  has been updated successfully, If not please update your Location or Inform to admin');
	                    		}
	                    		else{
	                    			alert('Your Current Location Not Updated');
	                    		}
	                    		
	                    	},
	                    	error:function(res){
	                    		alert("error");
	                    	}

	                    });
	                };
          		});
				
			});
		}
    }
</script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBV4Weec1XHiqbWpd_XFNhsnsRAfF6GRWs&libraries=places"
            async defer></script>

<?php }?>
<!--  Get Driver Locatuon End-->