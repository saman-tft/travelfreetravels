<?php
$template_images = $GLOBALS['CI']->template->template_images();
$loader_image_path = base_url() . '/extras/system/template_list/template_v3/images/image_loader.gif';
?>
<div class="fulloading result-pre-loader-wrapper">
    <div class="loadmask"></div>
    <div class="centerload cityload">
        <div class="load_links" style="position: absolute;top: 0;right: 0;z-index: 9999;font-size:14px;font-weight:300">
            <a href=""><i class="fa fa-refresh"></i></a>
            <a href="<?php echo base_url(); ?>"><i class="fa fa-close"></i></a>
        </div>
        <div class="loadcity"></div>
        <div class="clodnsun"></div>
        <div class="reltivefligtgo">
            <div class="flitfly"></div>
        </div>
        <div class="relativetop"></div>
        <div class="paraload2 text-center" id="processloadimg">
            <img src="<?= $loader_image_path ?>" alt="" />
            <h2 style="color:white; font-weight:bold;">Processing Your Booking...</h2>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<form id="form" action="<?= $form_url ?>" method="<?= $form_method ?>">
    <?php
    foreach ($form_params as $key => $val) { ?>
        <input type="hidden" name="<?= $key ?>" value="<?= $val ?>" />
    <?php } ?>
</form>

<script type="text/javascript">
    $('.fulloading').show();
    document.getElementById("form").submit();
</script>