<?php
//$country_list=$this->db_cache_api->get_agency_country_list();
?>
<div class="box box-danger">
  <div class="box-header with-border">
    <h3 class="box-title">
      <i class="fa fa-database"></i>Edit Product
    </h3>
  </div>
  <div class="box-body">
    <div class="row">
      
      <div class="col-md-7">
        
      </div>
      <div class="col-md-12">
        
        <!-- Tab panes -->
        

            <form method="POST" autocomplete="off" id="search_filter_form" action="<?php echo base_url()?>loyalty_program/saveproduct" enctype='multipart/form-data'>
              <input type="hidden" placeholder="Name" value="<?=@$get_edit_product['id']?>" name="id" class="search_filter form-control">
              <img src="<?php echo $GLOBALS['CI']->template->domain_uploads().'loyalty_product/'.$get_edit_product['image']?>" alt="Smiley face" height="42" width="42">
             <div class="clearfix form-group">
              <div class="col-xs-4">
                  <label>Product Type</label>
                  <select class="form-control" name="type" required="">                    
                      
                     <option value="">Select Type</option>
                     <option value="1" <?php if ($get_edit_product['type']==1){echo "selected";}?>>TFT Product</option>
                     <option value="2" <?php if ($get_edit_product['type']==2){echo "selected";}?>>Other Product</option>
                       
                  </select>
              </div>
              <div class="col-xs-4">
                  <label>Name</label>
                  <input type="text" placeholder="Name" value="<?=@$get_edit_product['name']?>" name="name" class="search_filter form-control">
              </div>
               <div class="clearfix form-group">
              <div class="col-xs-4">
                  <label>Description</label>
                  <input type="text" placeholder="Description" value="<?=@$get_edit_product['description']?>" name="description" class="search_filter form-control">
              </div>
               <div class="col-xs-4">
                  <label>Image</label>
                  <input type="file" placeholder="image" value="" name="image" class="search_filter form-control" >
              </div>
              <!--<div class="col-xs-4">
                  <label>Country</label>
                  <select class="form-control js-example-basic-multiple" name="country[]" multiple="multiple">
                      
                      <?php 
                         $cnlist=$get_edit_product['country'];
                         $cnlist1=explode(',', $cnlist);
                         debug($cnlist1);

                      foreach($country_list as $key=>$value) { ?>


                      <option value="<?=$key?>" <?php if (in_array($key, $cnlist1)){echo "selected";}?>><?=$value?></option>
                      <?php 
                       } 
                      ?>
                    </select>
              </div>-->
               <div class="clearfix form-group">
              <div class="col-xs-4">
                  <label>Point</label>
                  <input type="text" placeholder="Point" value="<?=@$get_edit_product['point']?>" name="point" class="search_filter form-control">
              </div>
              
                <!-- <div class="col-xs-4">
                  <label>Reward point</label>
                  <input type="text" placeholder="Reward point"  name="reward_point" class="search_filter form-control" value="<?=@$hreward_point?>">
                </div> -->
                
                
                
              </div>
              <div class="col-sm-12 well well-sm">
                <button class="btn btn-primary" type="submit">Submit</button> 

                <a class="btn btn-warning" href="<?php echo base_url();?>loyalty_program/product_list">Back</a>
              </div>
            </form>

            
        
        

          

          
          
          
         
            
          
          

       
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-multiple').select2({
       placeholder: 'Select Country',
    });
});

</script>