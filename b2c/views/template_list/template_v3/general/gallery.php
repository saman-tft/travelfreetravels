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

<style>
  .modal-backdrop.in {
    display: none;
}
.carousel-control.right {
    right: -70px;
}
.carousel-control.left {
    left: -70px;
}
.carousel {
    position: relative;
    overflow: hidden;
}
.carousel-inner {
    overflow: visible;
}
.gall_img .close1 {
    top: 3px;
    right: 5px;
    width: 40px;
    line-height: 37px;
    height: 40px;
    border-radius: 50px;
    z-index: 999999;
    background: #0e1938;
}

</style>
<div class="inner-banner">
    <div class="container-fluid">

        <img class="" src="<?php echo $slideImageJson[0]['image']; ?>" alt="Tour Packages" />
        <h1><?php echo $slideImageJson[0]['title']; ?></h1>
    </div>
</div>
<div class="clearfix"></div>









<script>
function openModal(image) {
    document.getElementById("modal_image").src = image;
    document.getElementById("myModal").style.display = "block";
}

function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("demo");
    var captionText = document.getElementById("caption");
    if (n > slides.length) {
        slideIndex = 1
    }
    if (n < 1) {
        slideIndex = slides.length
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
    captionText.innerHTML = dots[slideIndex - 1].alt;
}
</script>





<!--new code -->

<div class="gall_img">
    <div class="pagehdwrap">
        <h2 class="pagehding">Gallery Images</h2>
    </div>
    <div class="container text-center">

        <div class="grid_new">
            <div class="col-md-12">

                <?php $query = "SELECT * FROM gallery_images WHERE status='1' ORDER BY banner_order ASC";
			$get_data= $this->db->query($query)->result_array(); 
			foreach ($get_data as $key => $value) { ?>
                <div class="col-sm-3 col-xs-12 nopad htd-wrap">
                    <div class="effect-marley_new figure_newwe">

                        <img id="img-<?=$key?>" src="<?php echo base_url().IMG_UPLOAD_DIR.$value['image']; ?>"
                            alt="<?php echo $value['title'];?>" style="width:100%"
                            class="hover-shadow cursor btn btn-primary btn-lg tour-gallery-item" data-toggle="modal"
                            data-target="#ModalCarousel">


                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="ModalCarousel" tabindex="-1" role="dialog" aria-labelledby="ModalCarouselLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div id="carousel-modal-demo" class="carousel slide" data-ride="carousel" data-interval="false">

                        <!-- Sliding images statring here -->
                        <div class="carousel-inner"> 
                        <span class="close1 cursor">&times;</span>
                    <?php 
			foreach ($get_data as $key2 => $value) { ?>
<?php
$myclass="";
if($key2==0){ $myclass="active"; }
?>
    <div class="item <?=$myclass?>"> 
      <img class="img-<?=$key2?>" src="<?php echo base_url().IMG_UPLOAD_DIR.$value['image']; ?>" alt="banana"> 
    </div> 
    <?php } ?>
     
  </div> 
                        <!-- Next / Previous controls here -->
                     

                    </div>
                    <a class="left carousel-control" href="#carousel-modal-demo" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                        <a class="right carousel-control" href="#carousel-modal-demo" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click','.tour-gallery-item',function(){
  let itm=$(this).attr('id');
  $('.carousel-inner .'+itm).parent().addClass('active');
  $('.carousel-inner .'+itm).parent().siblings().removeClass('active');

});
$('.close1').on('click',function(){
$('#ModalCarousel').modal('hide');
});

</script>