<script src="<?php echo JAVASCRIPT_LIBRARY_DIR; ?>jquery-2.1.1.min.js"></script>
<?php if(is_logged_in_user() == true){ //If User is Logged in, then iclude the notification script?>
	<script src="<?php echo $GLOBALS['CI']->template->template_js_dir('page_resource/notification.js'); ?>" ></script>
<?php }?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDy0ptBNxSV5GjdJ4Wm7Fr0XG3UxQy-JDU" type="text/javascript"></script>