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


<div class="inner-banner">
	<div class="container-fluid">
     
     <img class="" src="<?php echo $slideImageJson[0]['image']; ?>" alt="" >
     <h1><?php echo $slideImageJson[0]['title']; ?></h1>
    </div>
</div>
<div class="clearfix"></div>

<div class="sect_gall">
	
<div class="pagehdwrap">
			<h2 class="pagehding">Videos</h2>
		</div>
	<div class="container">
		<div class="gallery_img">
		<?php $query = "SELECT * FROM gallery_url WHERE status='1' ORDER BY banner_order ASC";
		$get_data= $this->db->query($query)->result_array(); 
			foreach ($get_data as $key => $value) { ?>
			<div class="col-md-4">
			<iframe frameborder="0" allowfullscreen="" width="100%" height="250px" src="https://www.youtube.com/embed/<?php echo $value['url'];?>"></iframe> 
			</div>
		<?php } ?>
		</div>
	</div>
</div>

<div class="clearfix"></div>





