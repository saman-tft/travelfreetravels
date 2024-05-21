<?php
$active_domain_modules = $GLOBALS ['CI']->active_domain_modules;
$master_module_list = $GLOBALS ['CI']->config->item ( 'master_module_list' );
if (empty ( $default_view )) {
	$default_view = $GLOBALS ['CI']->uri->segment ( 1 );
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta
	content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'
	name='viewport'>
<title><?php echo get_app_message('AL001'). ' '.HEADER_TITLE_SUFFIX; ?></title>
		<?php 
// Loading Common CSS and JS
		$GLOBALS ['CI']->current_page->header_css_resource ();
		$GLOBALS ['CI']->current_page->header_js_resource ();
		?>
  </head>
<body id="page-top">
	<noscript>
		<img
			src="<?php echo $GLOBALS['CI']->template->template_images('default_loading.gif'); ?>"
			class="img-responsive center-block">
	</noscript>
	<!-- header -->
	<header>
  <nav id="mainNav" class="navbar navbar-default main_nav navbar-fixed-top">
    <div class="container"> 
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand page-scroll" href="<?=base_url()?>"><img src="<?php echo $GLOBALS['CI']->template->template_images('xenium_logo.png'); ?>" alt=""/></a> </div>
      
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
          <li> <a class="page-scroll" href="#about"><i class="sprite home_icn"></i></a> </li>
          
									<?php
										foreach ( $master_module_list as $k => $v ) {
											if (in_array ( $k, $active_domain_modules )) {
												?>
											<li
											class="<?=((@$default_view == $k || $default_view == $v) ? 'active' : '')?>"><a
											href="<?php echo base_url()?>general/index/<?php echo ($v)?>?default_view=<?php echo $k?>"> <?php echo ucfirst($v)?></a></li>
											<?php
											}
										}
										?>
          
          <li class="dropdown"> <a href="#" class="dropdown-toggle page-scroll" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="sprite rightmnu_icn"></i></a>
            <ul class="dropdown-menu">
             
                         <!-- Login Button Starts Here -->
								<?php if (is_logged_in_user() == true) { ?>
									
										<button type="button"
											class="btn btn-i b-r-0 navbar-btn "
											data-toggle="" aria-expanded="false">
											<?php echo $GLOBALS['CI']->entity_name?> <span class="caret"></span>
										</button>
										
											<li><a
												href="<?=base_url()?>index.php/user/profile/<?=@$GLOBALS['CI']->name?>">My
													Account</a></li>
											<li><a href="<?=base_url().'index.php/report/booking'?>">Booking</a></li>
											<li><a
												href="<?=base_url().'index.php/auth/change_password'?>">Change
													Password</a></li>
											<li class="divider"></li>
											<li><a
												href="<?=base_url().'index.php/auth/initilize_logout'?>">Logout</a></li>
										
									
									<?php } else { ?>
										<li><a href="#" data-toggle="modal" data-target="#myModal_1" class="btn btn-t b-r-0 navbar-btn">Sign In/Sign Up</a></li>
									<?php } ?>
              
                    <!-- Login Button Ends Here -->
              
              
              <li role="separator" class="divider"></li>
              <li class="dropdown">
                <h3>Select Currency</h3>
              </li>
              <li><a href="#"><strong>USD</strong></a></li>
              <li><a href="#"><strong>AED</strong></a></li>
              <li><a href="#"><strong>INR</strong></a></li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- /.navbar-collapse --> 
    </div>
    <!-- /.container-fluid --> 
  </nav>
</header>

	<!-- UTILITY NAV -->
	<div class="container-fluid utility-nav clearfix">
		<!-- ROW --> <?php
		if ($this->session->flashdata ( 'message' ) != "") {
			$message = $this->session->flashdata ( 'message' );
			$msg_type = $this->session->flashdata ( 'type' );
			$show_btn = TRUE;
			if ($this->session->flashdata ( 'override_app_msg' ) != "") {
				$override_app_msg = $this->session->flashdata ( 'override_app_msg' );
			} else {
				$override_app_msg = FALSE;
			}
			
			echo get_message ( $message, $msg_type, $show_btn, $override_app_msg );
		}
		?> <!-- /ROW -->
	</div>
	<!-- /UTILITY NAV -->

	<!-- end header -->
	<div class="wrapper">
		<!-- Wrapper Starts -->
		<?php echo $body;?>
	</div>
	<!-- ./wrapper -->

        
        
         
	<footer>
	<div class="footer_top">
    	<div class="container">
        
        	<div class="col-lg-3">
            <ul class="list-unstyled footer_links">
            	<li><a class="navbar-brand page-scroll" href="#page-top"><img src="<?php echo $GLOBALS['CI']->template->template_images('xenium_logo.png'); ?>" alt=""/></a></li>
            	<li><h3>Address</h3></li>
                <li><p>Bangalore - 560042
India
Phone : +91 0123456789</p></li>
            </ul>
            </div>
            
            <div class="col-lg-9 nopad">
            	<div class="col-lg-3">
                	<ul class="list-unstyled footer_links">
                    	<li><h3>About</h3></li>
                    	<li><a href="">Home</a></li>
                        <li><a href="">About Us </a></li>
                        <li><a href="">Team</a></li>
                        <li><a href="">Add your Hotel </a></li>
                        <li><a href="">Terms & Condition </a></li>
                        <li><a href="">Privacy Policy </a></li>
                        <li><a href="">Refund Policy  </a></li>
                        <li><a href="">Contact us</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3">
                <ul class="list-unstyled footer_links">
                    <li><h3>Traveler Tools</h3></li>
                    <li><a href="">Download Our App</a></li>
                    <li><a href="">Extranet</a></li>
                    <li><a href="">For new Hotels</a></li>
                    <li><a href="">Blog</a></li>
                    <li><a href="">Team</a></li>
                    <li><a href="">Add your Hotel</a></li>
                    <li><a href="">Terms & Condition</a></li>
                    <li><a href="">Privacy Policy</a></li>     
                </ul>
                </div>
                
                <div class="col-lg-3">
                <ul class="list-unstyled footer_links">
                    <li><h3>Legal</h3></li>
                    <li><a href="">Terms of use</a></li>
                    <li><a href="">Privacy Policy</a></li>
                    <li><a href="">Cookies Policy</a></li>
                    <li><a href="">Add your Hotel</a></li>
                    <li><a href="">Terms & Condition </a></li>
                    <li><a href="">Privacy Policy</a></li>
                    <li><a href="">Add your Hotel</a></li>
                    <li><a href="">Terms & Condition</a></li>     
                </ul>
                </div>
                
                <div class="col-lg-3">
                <ul class="list-unstyled footer_links">
                    	<li><h3>Booking</h3></li>
                    	<li><a href="">Hotel</a></li>
                        <li><a href="">Car</a></li>
                        <li><a href="">Sightseeing</a></li>
                        <li><a href="">Cruises</a></li>
                        <li><a href="">Group Travel</a></li>
                        <li><a href="">Extranet</a></li>
                        <li><a href="">For new Hotels</a></li>
                        <li><a href="">Blog</a></li>
                </ul>
                </div>
            </div>
            
        </div>
    </div>
	
    <div class="footer_bottom">
    	<div class="container">
        	
                <ul class="nav navbar-nav">
                	<li><a href="">Types of tickets</a></li>
                    <li><a href="">Visa</a></li>
                    <li><a href="">Sitemap</a></li>
                    <li><a href="">Legal</a></li>
                    <li><a href="">Privacy Policy</a></li>
                    <li><a href="">Accessibility</a></li>
                </ul>
        	<p>&copy; 2014 Privacy Policy </p>
        </div>
    
    </div>
        
            
</footer>
	<!-- LOGIN MODAL OPEN -->
	<?=$GLOBALS['CI']->template->isolated_view('general/login')?>
	<!-- LOGIN MODAL CLOSE -->
	<?php Provab_Page_Loader::load_core_resource_files();?>
	
	<script>
		$(document).ready(function(){			
			$("#newsemailbtn").click(function(e){
				var email_regex = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
				var email = $("#newsemail").val();
				if (!email.match(email_regex) || email.length == 0) {
				$('#newsemail').val("Enter valid email ID"); // This Segment Displays The Validation Rule For Email
				//$("#newsemail").focus();
				return false;
				} else {
					var base_url = '<?php base_url() ?>';
					$.ajax({
						url:base_url+"index.php/general/email_subscription",
						data: {email:email},
						success:function(data){							
							if(data == 'success'){
								$('#newsemail').val("successfully subscribed");
							}else if(data == 'already'){
								$('#newsemail').val("Already subscribed");
							}else{
								$('#newsemail').val("Subscribtion Failed");
							}
						}					
					});
				}			
		 });
		});
	</script>
    <script>
    /*$(window).scroll(function(){
	var sticky = $('#mainNav'),
      scroll = $(window).scrollTop();

  if (scroll >= 100){ sticky.addClass('navbar-fixed-top'); }
  else { sticky.removeClass('navbar-fixed-top'); }
}); */
    </script>
</body>
</html>
