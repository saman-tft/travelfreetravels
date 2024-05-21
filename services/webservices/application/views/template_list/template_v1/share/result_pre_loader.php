<div class="result-pre-loader">
	<div class="result-pre-loader-container">
        <div class="preloader_logo"><img src="<?php echo $GLOBALS['CI']->template->template_images('xenium_logo.png'); ?>"></div>
        <div class="clearfix"></div>
		<h3><b>Please wait a moment while we are preparing the content !</b></h3>
		<div class="progress progress-striped active">
			<div style="width: 0%;" role="progressbar" id="bar" class="progress-bar progress-bar-color"><span>Still working ... 100%</span></div>
		</div>
		<div class="row">
			<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 ">
				<div class="pull-left">
					<i class="icn ldep_f_icn"><img alt="" src="<?php echo $GLOBALS['CI']->template->template_images('dep_l.png'); ?>"></i>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6 ">
					<h4>BLR</h4>
					<h5>Bangalore</h5>
				</div>
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 "></div>
			<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 ">
				<div class="pull-right">
					<i class="icn larr_f_icn"><img src="<?php echo $GLOBALS['CI']->template->template_images('arr_l.png'); ?>"></i>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-6 pull-right arr_ldr_otr">
					<h4>MAA</h4>
					<h5>Chennai</h5>
				</div>
			</div>
		</div>
	</div>
</div>
 <script>
 function show_result_pre_loader()
 {
		var progress = setInterval(function () {
			var $bar = $("#bar");
			if ($bar.width() >= 600) {
				clearInterval(progress);
			} else {
				$bar.width($bar.width() + 60);
			}
			$bar.text($bar.width() / 6 + "%");
			if ($bar.width() / 6 == 100){
				$bar.text("Please Wait ... " + parseInt($bar.width() / 6) + "%");
			}
		}, 1000);
 }

 function hide_result_pre_loader()
 {
	$('.result-pre-loader').hide();
 }
</script>
