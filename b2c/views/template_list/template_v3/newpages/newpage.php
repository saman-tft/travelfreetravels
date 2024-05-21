<style>
	.message_box{background: #fff;padding: 30px 20px;margin: 5% auto;width: 50%; display: block;border: 2px solid red;}
	.message_box p{color: #000;font-size: 26px;text-align: center;}
	.ful_logoo{
		display: block;
		margin: 20px auto;
	}
</style>



<div class="message_box">
 <a href="<?= base_url() ?>"><img class="ful_logoo" src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template->get_domain_logo()); ?>"	alt=""/></a>
	<p>Sorry For the inconvenience </p>
		<p>we are not accepting payment</p>
</div>