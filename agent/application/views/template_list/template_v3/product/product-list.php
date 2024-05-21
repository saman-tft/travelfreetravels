<style>
.mt-50 {
    margin-top: 50px
}

.mb-50 {
    margin-bottom: 50px
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
    border-radius: 15px;
}

.card-img-actions {
    position: relative
}

.card-body {
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 10px;
    text-align: center;
}

.card-img {
    width: 90%;
    border: 1px solid #ccc;
    border-radius: 10px;
    height: 250px;
    object-fit: contain;
}

.star {
    color: red
}

.bg-cart {
    background-color: #009edb;
    color: #fff;
    border-radius: 50px;
    padding: 2px 10px;
    min-width: 100px;
    margin-top: 10px;
}

.bg-cart:hover {
    color: #fff
}

.bg-buy {
    background-color: green;
    color: #fff;
    padding-right: 29px
}

.bg-buy:hover {
    color: #fff
}

a {
    text-decoration: none !important
}

.col-md-4.mt-2.cards-prducts {
    margin-bottom: 2%;
}

h6 a.text-default {
    font-size: 20px;
}

.fas.fa-coins:before {
    content: "\f51e";
}

.fa,
.fas {
    font-family: 'Font Awesome 5 Pro';
    font-weight: 900;
}
a.addbutton.product-list-icon {
    background: none;
    border: none;
}
a.addbutton.product-list-icon img {
    width: 30px;
}
.btn.bg-cart img {
    width: 16px;
    filter: brightness(0) invert(1);
    position: relative;
    top: -1px;
}
</style>
<div class="container d-flex justify-content-center mt-50 mb-50">
    <div class="row">
        <div class="step_head">
            <h3 class="dashhed">Check out the Products</h3>
            <a class="addbutton product-list-icon"> <img src="https://www.travelfreetravels.com/extras/system/template_list/template_v3/images/icon-coin.png" alt=""></a>
        </div>
        <?php
        for($i=0;$i<count($product_data);$i++)
        {
            if(!in_array($product_data[$i]['id'],$checkproduct_data))
            {
        ?>
        <div class="col-md-4 mt-2 cards-prducts">
            <div class="card">
                <div class="card-body">
                    <div class="card-img-actions"> <img
                            src="https://travelfreetravels.com/extras/custom/TMX6244821650276433/uploads/loyalty_product/<?php echo $product_data[$i]['image'] ?>"
                            class="card-img img-fluid" width="96" height="350" alt=""> </div>
                </div>
                <div class="card-body bg-light text-center">
                    <div class="mb-2">
                        <h6 class="font-weight-semibold"> <a href="#" class="text-default"
                                data-abc="true"><?php echo $product_data[$i]['name'] ?></a> </h6> <a href="#"
                            class="text-muted" data-abc="true"><?php echo $product_data[$i]['description'] ?></a>
                    </div>

                    <a href="<?php echo base_url() ?>loyalty_program/redeem_product/<?php echo $product_data[$i]['id'] ?>/<?php echo $product_data[$i]['point'] ?>" class="btn bg-cart"> <img src="https://www.travelfreetravels.com/extras/system/template_list/template_v3/images/icon-coin.png" alt=""> <?php echo $product_data[$i]['point'] ?></a>
                </div>
            </div>
        </div>
        <?php
            }
        }
     ?>
    </div>
</div>