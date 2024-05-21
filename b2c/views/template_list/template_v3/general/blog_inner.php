<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<style type="text/css">
  .general-padding.login_bgs.blog-page {
    width: 100%;
    float: left;
    padding-top: 45px;
}
.blog-row {
    margin-top: 40px;
}
.blog-left-wrap {
    padding: 20px;
    border: solid 1px #E6E6E6;
    margin-bottom: 23px;
}
.blog-left-wrap {
    border-radius: 8px;
}
.blog-head h4 {
    font-size: 26px;
    line-height: 1.4;
    margin-bottom: 15px;
}
.blog-left-wrap .center-block {
    max-width: 100%;
    height: auto;
}
.blog-left-wrap .center-block {
    width: 100%;
    margin-bottom: 20px;
}
.circ-contents {
    margin-bottom: 15px;
}
.blog-right-wrap {
    padding: 20px;
    border: solid 1px #E6E6E6;
}
.blog-right-wrap {
    border-radius: 8px;
}
.blg-detail {
    display: flex;
}
.blog-head h4 {
    font-size: 26px;
    line-height: 1.4;
    margin-bottom: 15px;
}
.blog-right-info ul {
    margin: 0;
    padding: 0;
}
.blog-right-wrap ul li {
    overflow: hidden;
    margin: 0 0 1.5em;
}
.blog-right-info ul li a img {
    display: inline;
    float: left;
    margin: 0 12px 0 0;
    width: 60px;
    height: 60px;
    object-fit: cover;
}
.bloginertitl {
    font-size: 16px;
    color: #000;
}

.circ-contents p {
    font-size: 14px;
    text-align: justify;
    color: #000;
}


  .circ-contents ul li { 
    list-style: disc;
  }
  .circ-contents ol li { 
    list-style: disc;
  }
  .blog_head{float:right!important;}
</style>
<?php

function youtube_url_to_embed($youtube_url) {

    $search = '/youtube\.com\/watch\?v=([a-zA-Z0-9]+)/smi';

    $replace = "youtube.com/embed/$1";

    $embed_url = preg_replace($search,$replace,$youtube_url);

    return $embed_url;

} 

?>


<section class="general-padding login_bgs blog-page">

    <?php 
    $get_id = $this->uri->segment(3);
    $query = "SELECT * FROM blog WHERE origin='".$get_id."' AND status='1'";
    $get_data= $this->db->query($query)->result();  
 ?>
    <div class="container">
        <div class="row blog-row">
            <div class="col-md-8">
                <div class="blog-left-wrap ss">
                    <div>
                        <div class="blog-head"><h4><?php echo $get_data[0]->title?></h4></div>
                        <input type="hidden" name="image_name" id="image_name" value="logoOutside blog.jpg" />
                        <div class="circle col-md-12 nopad" id="image" style="">
                            <img class="center-block" src="<?php echo base_url().IMG_UPLOAD_DIR.$get_data[0]->image ?>" data-target="#myModal72" data-toggle="modal" id="img_modal72" alt="" />
                        </div>
                        <div class="circ-contents">
                            <p></p>
                            <div style="border-bottom: solid #eaecef 1pt; padding: 0cm 0cm 11pt 0cm;">
                                <p>
                                    <span style="font-size: 14px;">
                                        <span style="font-family: Arial, Helvetica, sans-serif;">
                                            <span style="background-color: white;">
                                                <span style="color: #212529;">
                                                    <?php echo $get_data[0]->description;?>
                                                </span>
                                            </span>
                                        </span>
                                    </span>
                                </p>
                            </div>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="blog-right-wrap">
                    <div class="blog-head"><h4>Recent Blogs</h4></div>
                    <div class="blog-right-info">
                    <?php 
                    $get_id = $this->uri->segment(3);
                    $query = "SELECT * FROM blog WHERE origin!='".$get_id."' AND status='1' ORDER BY origin DESC LIMIT 10";
                    $get_data= $this->db->query($query)->result_array(); 
                    foreach ($get_data as $key => $value) { 
                    if($value['origin']!=''){
                    ?>
                        <ul>
                            <!-- <a href="<?php echo base_url().'index.php/general/blog_inner/'.$value['origin']; ?>"> </a> -->
                            <a href="<?php echo base_url().'index.php/general/blog_inner/'.$value['origin']; ?>"> </a>
                            <li>
                                <a href="<?php echo base_url().'index.php/general/blog_inner/'.$value['origin']; ?>">
                                    <img src="<?php echo base_url().IMG_UPLOAD_DIR.$value['image'] ?>" class="attachment-75x75 size-75x75 wp-post-image" alt="<?php echo $get_data[0]->title;?>" width="75" height="75" loading="lazy" />
                                    <div class="bloginertitl blog-post-title68"><?php echo $value['title'];?></div>
                                </a>
                            </li>
                        </ul>
                    <?php } else { ?>
                        <ul>
                            <li>
                                <p>No Recent Blogs</p>
                            </li>
                        </ul>
                    <?php }  
                } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="What-is-it-Like-to-Work-in-France-Without-Speaking-French" class="blg-detail">
        <div class="general-padding1 hide">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h1 class="col-org">Blogs</h1>
                        <div class="br-botm"></div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</section>





