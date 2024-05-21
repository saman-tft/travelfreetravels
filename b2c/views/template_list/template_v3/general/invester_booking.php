<?php
$active_domain_modules = $this->active_domain_modules;
$default_active_tab = $default_view;

function set_default_active_tab($module_name, &$default_active_tab) {
	if (empty ( $default_active_tab ) == true || $module_name == $default_active_tab) {
		if (empty ( $default_active_tab ) == true) {
			$default_active_tab = $module_name; // Set default module as current active module
		}
		return 'active';
	}
}

//add to js of loader
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('backslider.css'), 'media' => 'screen');
Js_Loader::$css[] = array('href' => $GLOBALS['CI']->template->template_css_dir('owl.carousel.min.css'), 'media' => 'screen');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('owl.carousel.min.js'), 'defer' => 'defer');
 Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('backslider.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/index.js'), 'defer' => 'defer');
Js_Loader::$js[] = array('src' => $GLOBALS['CI']->template->template_js_dir('page_resource/pax_count.js'), 'defer' => 'defer');
?>

<div class="clearfix"></div>



<div class="gall_img">
	<div class="container">
		<div class="pagehdwrap">
			<h2 class="pagehding">Invester Booking</h2>
		</div>
		<div class="grid_new">
			<div class="col-md-12">
				<p style="color: green;font-size: 18px;">Your Invester Booking Details has been Submited Successfully. Admin Will contact you soon !!!</p>
			</div>
		</div>
	</div>
</div>




