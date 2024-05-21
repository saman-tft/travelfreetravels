<?php //Images Url
$template_images = $GLOBALS['CI']->template->template_images();
if($flight_search_params['trip_type'] == 'multicity') {
	$flight_search_params['depature'] = $flight_search_params['depature'][0];
	$flight_search_params['from'] = $flight_search_params['from'][0];
	$flight_search_params['to'] = end($flight_search_params['to']);
}
?>
<style>
	.topssec::after{display:none;}
</style>
<div class="modfictions layout_modification">
	<div class="modinew">
		<div class="container">
			<div class="contentsdw">
				<div class="smldescrptn">
					<div class="col-sm-7 col-xs-10 nopad">
						<div class="col-xs-6 boxpad none_boil_full">
							<h4 class="contryname"><?=ucfirst($flight_search_params['trip_type_label'])?> Trip</h4>
							<h3 class="placenameflt"><?php echo $flight_search_params['from'] ?> to <?php echo $flight_search_params['to'] ?></h3>
						</div>
						<?php 
						if($flight_search_params['trip_type']=='oneway') {
							$disable_return_date_label = ' style="opacity:0.4" ';
						} else {
							$disable_return_date_label = '';
						}
						?>
						<div class="col-xs-3 boxpad none_boil">
							<div class="boxlabl"><span class="faldate fal fa-calendar"></span>Departure</div>
							<div class="datein">
								<span class="calinn"><?=date('D, d M', strtotime($flight_search_params['depature']))?></span>
							</div>
						</div>
                        
						<div class="col-xs-3 boxpad none_boil" <?=$disable_return_date_label?>>
							<?php if($flight_search_params['trip_type']=='circle') { ?>
							<div class="boxlabl"><span class="faldate fal fa-calendar"></span>Return</div>
							<?php } ?>
							<div class="datein">
								<span class="calinn">
								<?php
									if($flight_search_params['trip_type']=='circle') {
										if(isset($flight_search_params['return']) == true) {
											$temp_return_date = $flight_search_params['return'];
										} else {
											$temp_return_date = $flight_search_params['depature'];
										}
										echo date('D, d M', strtotime($temp_return_date));
									} 
								?>
								</span>
							</div>
						</div>
					</div>
					<div class="col-sm-5 col-xs-2 nopad">
						<div class="col-xs-3 boxpad none_mody">
							<div class="boxlabl textcentr">Adult</div>
							<div class="countlbl"><?php echo $flight_search_params['adult_config']; ?></div>
						</div>
						<div class="col-xs-3 boxpad none_mody">
							<div class="boxlabl textcentr">Child</div>
							<div class="countlbl"><?php echo $flight_search_params['child_config']; ?></div>
						</div>
						<div class="col-xs-3 boxpad none_mody">
							<div class="boxlabl textcentr">Infant</div>
							<div class="countlbl"><?php echo $flight_search_params['infant_config']; ?></div>
						</div>
						<div class="col-xs-3 boxpad pull-right">
							<a id="modidown"  class="modifysrch" data-toggle="collapse" data-target="#modify"><span class="mdyfydsktp">Modify Search</span>
                            <i class="fa fa-angle-down mobresdv modu" aria-hidden="true"></i>
							</a>

						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="splmodify">
		<div class="container">
			<div id="modify" class="collapse araeinner">
				<div class="insplarea">
					<?php echo $GLOBALS['CI']->template->isolated_view('share/flight_search') ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.modifysrch').click(function(){
		$(this).stop( true, true ).toggleClass('up');
		$('.search-result').stop( true, true ).toggleClass('flightresltpage');
		$('.modfictions').stop( true, true ).toggleClass('fixd');

		if ($('.mdyfydsktp').text() == "Close") {
			$('.mdyfydsktp').text("Modify Search");
		} else {
			$('.mdyfydsktp').text("Close");
		}
		
	});

});

</script>

<script>
	$('#modidown').click(function() {
    $('#modify').toggle('1000');
    $("i", this).toggleClass("fa-angle-down fa-angle-up");
});
</script>