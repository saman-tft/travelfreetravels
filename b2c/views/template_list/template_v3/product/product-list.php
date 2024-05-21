<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .mb-50 {
        margin-bottom: 50px;
    }

    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: 10px;
    }

    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 10px;
        text-align: center;

        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
    }

    a {
        text-decoration: none !important;
    }

    h6 a.text-default {
        font-size: 20px;
    }

    a.addbutton.product-list-icon {
        background: none;
        border: none;
    }

    .mt-20 {
        margin-top: 20px;
    }

    .alert-success {
        background-color: #009edb !important;
        padding: 5px 10px !important;
    }

    /* changes start of styles for product carousel */
    .product__container h2 {
        background-color: #1a73e8;
        margin-top: 0;
        margin-bottom: 0;
        padding: 10px;
        text-align: center;
        color: white;
    }

    .product__container {
        margin-top: 2em;
        border: 1px solid #1a73e8;
        padding: 0;
        /* max-width: 300px; */
        overflow: hidden;
        /* background-color: white; */
    }

    .product__image img {
        width: 100% !important;
        /* border: 1px solid #ccc; */
        border: none;
        border-radius: 10px;
        height: 200px !important;
        object-fit: contain;
    }

    .product__button {
        display: grid;
        place-content: center;
        text-align: center;
        margin-bottom: 0;
        padding: 3px;
        width: 100%;
        margin-top: 1em;
        height: 40px;
        /*border-bottom-left-radius: 30px;*/
        /*border-bottom-right-radius: 30px;*/
        background-color: purple;
        color: white;
        font-size: 1.8rem;
    }

    .product__button:hover {
        color: white;
    }

    .buttons__container a {
        text-decoration: none;
        color: white;
    }

    .product__name {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
        -webkit-line-clamp: 2;
        --line-height: 1.2;
        line-height: var(--line-height);
        min-height: calc(2 * var(--line-height) * 1.2em);
        max-height: calc(2 * var(--line-height) * 1.2em);
        margin-bottom: 0;
        font-weight: 800;
        font-family: monospace;
        font-size: 20px;
        text-align: center;
        color: purple;
    }

    #redeemModal {
        position: fixed !important;
    }

    .product__point {
        margin-bottom: 0;
        font-weight: 800;
        font-family: monospace;
        font-size: 20px;
        text-align: center;
        color: purple;
        display: flex;
        align-items: center;
        margin: auto;
    }

    .product__point .product__star {
        font-size: 10px;
        padding: 5px;
        color: white;
        border-radius: 100%;
        background-color: #009edb;
    }

    .product__star {
        font-size: 17px;
        padding: 7px;
        border-radius: 100%;
        background-color: #009edb;
    }

    .product__star:hover {
        color: white !important;
    }

    .buttons__container {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .product__summary {
        margin-top: 5px;
        margin-bottom: 5px;
        margin-left: 8px;
        margin-right: 8px;
        min-height: 150px;
        max-height: 150px;
        text-align: justify;
        /* hyphens: auto; */
        overflow-y: auto;
        color: black;
    }

    .slick-slide {
        padding: 0;
        margin: 5px;
    }

    .slick-prev {
        left: 0;
    }

    .slick-next {
        right: 0;
    }

    .slick-prev,
    .slick-next {
        transform: translateY(-50%);
        position: absolute;
        top: 50%;
        z-index: 1;
        width: 40px;
        height: 40px;
        background-color: rgba(26, 115, 232, 0.1);
        border: none;
        border-radius: 5%;
        font-size: 20px;
        color: #1a73e8;
    }

    .slick-prev:hover,
    .slick-next:hover {
        background-color: rgba(26, 115, 232, 0.2);
        /* Adjust hover background color */
        color: white;
        /* Adjust hover text color */
    }

    #product-carousel .item,
    #product-carousel-next .item {
        padding: 1em 0 1em 0;
        width: 100%;
    }

    .reward-info {
        font-size: 18px;
        margin-bottom: 10px;
        color: black;
    }

    .used-rewards,
    .available-rewards {
        font-weight: bold;
        color: #1a73e8;
    }

    .dashhed {
        color: black;
    }

    /* modal */
    .modal-header {
        background-color: #009edb;
        color: white;
        padding: 10px;
        border-radius: 10px 10px 0 0;
    }

    .modal-title {
        font-size: 24px;
    }

    /* Modal Body */
    .modal-body {
        padding: 20px;
        font-size: 16px;
        color: black;
    }

    /* Modal Footer */
    .modal-footer {
        background-color: #f7f7f7;
        border-top: none;
        padding: 15px;
        border-radius: 0 0 10px 10px;
    }

    /* Close Button */
    #redeemModal .close {
        color: white;
        font-size: 24px;
        margin-top: 15px !important;
        margin-right: 10px !important;
        background-color: transparent !important;
    }

    #redeemModal .close:hover,
    #redeemModal .close:focus {
        color: white;
        text-decoration: none;
    }

    /* Button Styles */
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #009edb;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0077b6;
    }

    .btn-secondary {
        background-color: #f7f7f7;
        color: #333;
    }

    .btn-secondary:hover {
        background-color: #eaeaea;
    }

    /* alert message */
    .utility-nav button.close {
        right: 0 !important;
        top: 7px !important;
        padding: 0 !important;
    }

    .alert-success {
        position: relative;
        padding: 15px !important;
        margin-bottom: 20px !important;
        border: 1px solid transparent;
        border-radius: 4px !important;
        color: #155724 !important;
        background-color: #d4edda !important;
        border-color: #c3e6cb !important;
        width: 80% !important;
        text-align: center;
        margin: auto;
    }

    .alert-success .close {
        position: absolute !important;
        top: 0 !important;
        right: 0 !important;
        font-size: 24px !important;
        background-color: transparent !important;
        color: #155724 !important;
    }

    .alert-success .close:hover {
        color: #155724 !important;
    }

    /* changes end of styles for product carousel */
</style>

<div class="modal fade" id="redeemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Redeem Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body" id="modal_body_product">
            </div>
            <div class="modal-footer" id="modal_footer_product">
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="" id="product_data" value="<?php echo htmlspecialchars(json_encode($product_data)); ?>" readonly>
<div class="container d-flex justify-content-center mt-20 mb-50">
    <?php
    if (isset($message)) {
    ?>
        <div class="alert alert-success alert-message" role="alert">
            <?= $message ?>
        </div>
    <?php
    }
    ?>

    <div class="row">
        <div class="step_head">
            <h3 class="dashhed">Check out the Products</h3>
            <a class="addbutton product-list-icon"><i class="fa fa-star product__star"></i></a>
        </div>
        <p class="reward-info">Total Used Rewards: <span class="used-rewards"><?php echo ($user['used_reward'] > 0) ? $user['used_reward'] : 0; ?></span></p>
        <p class="reward-info">Available Rewards: <span class="available-rewards"><?php echo ($user['pending_reward'] > 0) ? $user['pending_reward'] : 0; ?></span></p>

        <!-- changes start of product carousel -->
        <?php
        if (!empty($product_data) && count($product_data) > 0) {
            $ref = 0;
            $redeemable_reward = array();
            $next_reward = array();
            foreach ($product_data as $p_k => $p_v) {
                if ($user['pending_reward'] > $p_v['point']) {
                    array_push($redeemable_reward, $p_v);
                } else {
                    array_push($next_reward, $p_v);
                    $ref++;
                }
            }
        ?>
            <div class="product__container">
                <h2>Redeem Products</h2>
                <div id="product-carousel" class="carousel">
                    <?php
                    if (count($redeemable_reward) > 0) {
                        foreach ($redeemable_reward as $p_k => $p_v) {
                    ?>
                            <div class="item">
                                <div pid="<?php echo $p_v['id']; ?>" class="card">
                                    <div class="card-body">
                                        <div class="product__image">
                                            <figure>
                                                <img src="<?= base_url() ?>extras/custom/TMX6244821650276433/uploads/loyalty_product/<?php echo $p_v['image'] ?>" alt="<?php echo $p_v['name']; ?>">
                                                <figcaption></figcaption>
                                            </figure>
                                        </div>
                                        <div class="product__point">
                                            <i class="fa fa-star product__star"></i>&nbsp;<span><?php echo $p_v['point']; ?></span>
                                        </div>
                                        <div class="product__name">
                                            <p><?php echo $p_v['name']; ?></p>
                                        </div>
                                        <div class="product__summary">
                                            <p><?php echo $p_v['description']; ?></p>
                                        </div>
                                        <div class="buttons__container">
                                            <a id="redeem_products_button_<?php echo $p_v['id']; ?>" class="btn product__button"> Redeem Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <p style="color:black; text-align:center;">You do not have enough points to redeem products.</p>
                <?php
                    }
                }
                ?>
                </div>
            </div>
            <?php
            if (!empty($next_reward) && count($next_reward) > 0) {
            ?>
                <div class="product__container">
                    <h2>Next Products</h2>
                    <div id="product-carousel-next" class="carousel">
                        <?php
                        foreach ($next_reward as $p_k => $p_v) {
                        ?>
                            <div class="item">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="product__image">
                                            <figure>
                                                <img src="<?= base_url() ?>extras/custom/TMX6244821650276433/uploads/loyalty_product/<?php echo $p_v['image'] ?>" alt="<?php echo $p_v['name']; ?>">
                                                <figcaption></figcaption>
                                            </figure>
                                        </div>
                                        <div class="product__point">
                                            <i class="fa fa-star product__star"></i>&nbsp;<span><?php echo $p_v['point']; ?></span>
                                        </div>
                                        <div class="product__name">
                                            <p><?php echo $p_v['name']; ?></p>
                                        </div>
                                        <div class="product__summary">
                                            <p><?php echo $p_v['description']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js" integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/textfit/2.4.0/textFit.min.js" integrity="sha512-vLs5rAqfvmv/IpN7JustROkGAvjK/L+vgVDFe7KpdtLztqF8mZDfleK2MZj/xuOrWjma0pW+lPCMcBbPKJVC7g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    $(document).ready(function() {
        let product_data = JSON.parse(document.getElementById('product_data').value);

        // Event handler for clicks on .product__button elements
        // $('[id^=redeem_products_button_]').on('click', function(e) {
        $(document).on('click', '.product__button', function(e) {
            e.preventDefault();
            console.log($(this).parent().parent().parent().attr('pid'));
            // let productId = $(this).attr('id').split('_')[3];
            let productId = $(this).parent().parent().parent().attr('pid');
            handleRedeem(productId);
        });

        // Function to handle redeeming the product
        function handleRedeem(productId) {
            let product = product_data.find(item => item.id === productId); // Find the product object
            if (product) {
                $("#modal_body_product").html("");
                $("#modal_body_product").html("Please confirm that you want to redeem " + product.name + " for " + product.point + " reward points. The equivalent reward points will be deducted from your account.");
                $("#modal_footer_product").html("");
                $("#modal_footer_product").html('<button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>\
                <a href="<?php echo base_url() ?>user/redeem_product/' + product.id + '/' + product.point + '" type="button" class="btn btn-success">Confirm</a>');
                $("#redeemModal").modal('show');
            }
        }
    });
</script>

<!-- changes added js for the product carousel -->
<script>
    $(document).ready(function() {
        // Function to adjust font size to fit within two lines
        function adjustFontSize() {
            $('.product__name').each(function() {
                // Adjust font size to fit within the container
                textFit($(this)[0], {
                    alignVert: true, // Vertical alignment
                    alignHoriz: true, // Horizontal alignment
                    multiLine: true, // Allow multiple lines
                    minFontSize: 10, // Minimum font size
                    maxFontSize: 20 // Maximum font size
                });
            });
        }

        // Call the adjustFontSize function initially and on window resize
        adjustFontSize();
        $(window).resize(adjustFontSize);

        $("#product-carousel").slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            dots: false,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
            responsive: [{
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 2,
                        arrows: false
                    }
                },
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 1,
                        arrows: false
                    }
                }
            ]
        });

        $("#product-carousel-next").slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 3000,
            dots: false,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
            responsive: [{
                    breakpoint: 769,
                    settings: {
                        slidesToShow: 2,
                        arrows: false
                    }
                },
                {
                    breakpoint: 481,
                    settings: {
                        slidesToShow: 1,
                        arrows: false
                    }
                }
            ]
        });
    });
</script>