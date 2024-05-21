<!DOCTYPE html>
<html>
<head>
	<title>about us</title>
</head>
<body>
<!-- <section class="about-banner">
        <div class="container">
            <div class="row">
                <h3>About Travel Free Travel</h3>
            </div>
        </div>
    </section> -->
    <style>.fromtopmargin{background: #fff!important;} 
.cms_banner{  /* height: 250px;*/ 
    width: 100%;
    object-fit: cover;}
.single-choose-us h2{font-size: 26px;
    margin-bottom: 5px;
    color: #009edb;
    text-transform: capitalize;}
.single-choose-us p{font-size: 12px;color: #505565;text-align: justify;}</style>
    <img class="cms_banner" src="../extras/system/template_list/template_v3/images/cms_banner.png">
    <section class="tour-cta a-bout">
        <div class="container-fluid nopad">
            <?php 

            for ($i=0; $i <count($adt_us) ; $i++) { 
                $adv_text=$adt_us[$i]['text'];
                $adv_image=$adt_us[$i]['image'];
                $adv_module=$adt_us[$i]['module'];
            


             ?>
            <div class="tour-cta-main">



                <div class="row">
                    <?php if( $i % 2 == 0){ ?>
                    <div class="row color-a-variant1 ">
                        <div class="container">
                            <div class="col-md-5 col-sm-12">
                                <div class="image-content"><img
                                        src="<?php echo $GLOBALS['CI']->template->domain_images($adv_image); ?>"
                                        alt="travel agency worldwide" /></div>
                            </div>
                            <div class="col-md-7 col-sm-12 nopad">
                                <div class="pagehdwrap about-head-main">


                                    <h2 class="pagehding about-head"><?php echo "About TFT" ?></h2>
                                </div>
                                <div class="choose-us-wrapper">
                                    <div class="single-choose-us">
                                        <p><?php echo $adv_text ?>.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <?php }else{ ?>

                    <div class="row color-a-variant2" >
                        <div class="container">
                            <div class="col-md-7 col-sm-12 nopad">
                                <div class="pagehdwrap about-head-main">


                                    <h2 class="pagehding about-head"><?php echo $adv_module ?></h2>
                                </div>
                                <div class="choose-us-wrapper">
                                    <div class="single-choose-us">
                                        <p><?php echo $adv_text ?>.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-12">
                               <div class="image-content"><img
                                        src="<?php echo $GLOBALS['CI']->template->domain_images($adv_image); ?>"
                                        alt="travel agency worldwide" /></div>
                            </div>
                        </div>

                    </div>


                    <?php } ?>
                </div>


            </div>
            <?php  if($i==10)break; } ?>

        </div>
    </section>
</body>
</html>