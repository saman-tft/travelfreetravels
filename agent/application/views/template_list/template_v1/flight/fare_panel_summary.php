<?php //Images Url
$template_images = $GLOBALS['CI']->template->template_images();
?>
	<main class="search-result-main modifysectn">
		<div class="container">
			<div class="panel panel-default b-r-0">
				<div class="panel-body p-0">
					<div class="row m-0 lg-flex">
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 p-tb-5 lg-text-center b-r rslt_iconcntr">
							<img src="<?=$template_images?>icons/flight-search-result-up-icon.png" alt="Flight Search Result Up Icon">
							<h1 class="h6"><?php echo $flight_search_params['from'] ?></h1>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-6 p-tb-5 lg-text-center b-r rslt_iconcntr">
							<img src="<?=$template_images?>icons/flight-search-result-down-icon.png" alt="Flight Search Result Down Icon">
							<h1 class="h6"><?php echo $flight_search_params['to'] ?></h1>
						</div>
						<div class="clearfix visible-sm-block"></div>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 p-tb-5 b-r">
							<h2 class="h4">Journey</h2>
							<?php
							$date_tag = '';
							if (strtolower($flight_search_params['trip_type']) == 'oneway') {
								echo '<h3 class="h5">OneWay Trip</h3>';
								$date_tag .= '<p class="h6 m-b-0">'.date('M',strtotime($flight_search_params['depature']));
							} elseif (strtolower($flight_search_params['trip_type']) == 'circle') {
								echo '<h3 class="h5">Round Trip</h3>';
								$date_tag .= '- '.date('M',strtotime($flight_search_params['return']));
							} else {
								echo '<h3 class="h5">Round Trip</h3>';
							}
							$date_tag .= '</p>';
							echo $date_tag;
							$total_pax = ($flight_search_params['adult']);
							?>
						</div>
						<div class="clearfix visible-md-block"></div>
						<div class="col-lg-1 col-md-4 col-sm-6 col-xs-6 p-tb-5 b-r">
							<h2 class="h4">Class</h2>
							<h3 class="h5"><?php echo $flight_search_params['cabin'] ?></h3>
						</div>
						<div class="clearfix visible-sm-block"></div>
						<div class="col-lg-4 col-md-4 col-sm-6 p-tb-5">
							<br>
							<button type="button" class="btn btn-lg btn-block btn-p b-r-0 flight_search_form" data-toggle="collapse" href="#flight_search_form">Modify Search</button>
						</div>
					</div>
				<div id="flight_search_form" class="collapse">
					<div class="panel panel-default m-0 b-r-0">
						<div class="panel-body">
							<?php echo $GLOBALS['CI']->template->isolated_view('share/flight_search') ?>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</main>