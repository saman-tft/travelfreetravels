



<!-- HTML BEGIN -->

<head>

   <script src="http://demo.itsolutionstuff.com/plugin/croppie.js"></script>

   <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/croppie.css">

</head>

<div class="top_header addmn">

       

<?=$GLOBALS['CI']->template->isolated_view('activity/activity_list_tab')?>

</div>

<div id="package_types" class="bodyContent col-md-12 yhgjk">

   <div class="panel panel-default">

      <!-- PANEL WRAP START -->

      <div class="panel-heading">

         <!-- PANEL HEAD START -->

         <div class="panel-title">

            <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">

               <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->

               <li role="presentation" class="active">

                  <a href="#fromList"

                     aria-controls="home" role="tab" data-toggle="tab">

                     <h1>View Admin Excursions</h1>

                  </a>

               </li>

               <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->

            </ul>

         </div>

      </div>

      <!-- PANEL HEAD START -->

      <div class="panel-body">

         <!-- PANEL BODY START -->

         <div class="tab-content">

            <div role="tabpanel" class="tab-pane active" id="fromList">

               <div class="col-md-12 bxpd">

                  <div class='row'>

                     <div class='col-sm-12 bxpd'>

                        <div class='' style='margin-bottom: 0;'>

                           <div class=' '>

                              <div class='actions'>

                                 <a href="<?php echo base_url(); ?>index.php/activity/add_with_price">

                                 <button class='btn btn-primary' style='margin-bottom: 5px'>

                                 + Add Excursion</button>

                                 </a> <a href="#"><i> &nbsp</i></a>

                              </div>

                           </div>

                           <?php if(isset($status)){echo $status;}?>

                           <div class='responsive-table'>

                              <div class=''>

                                 <div class='scrollable-area'>

                                    <table class='table table-condensed table-bordered table-striped external'

                                       style='margin-bottom: 0;table-layout:fixed;'>

                                       <thead>

                                          <tr>

                                            <?php $j=1; ?>

                                            <th>Sl.No</th>

                                             <th width="9%"><input type='checkbox' name='alll' id='selectall<?=$j?>' onclick='checkall(<?=$j?>);'>&nbsp;&nbsp;&nbsp;<b>Select All </b>

        <div class="dropdown2" role="group" style="float:right;">

                                <div class="dropdown slct_tbl pull-left sideicbb hjkuu"> <i class="fa fa-ellipsis-v"></i>

                                    <ul class="dropdown-menu sidedis" style="display: none;">

                                        <li> <a href="#" class="sideicbb3 sidedis" onclick="manage_details(<?=$j?>,'deactivate');"><i class="fa fa-toggle-off" ></i>Deactivate</a> </li>

                                        <li> <a href="#" class="sideicbb3 sidedis" onclick="manage_details(<?=$j?>,'activate');"><i class="fa fa-toggle-on" ></i>Activate</a> </li>

                                        <li> <a href="#" class="sideicbb3 sidedis" onclick="manage_details(<?=$j?>,'delete');"><i class="fa fa-trash" ></i>Delete</a> </li>

                                    </ul>

                                </div>

                            </div></th>

                                             <th>Action</th>

                                             <th>Excursion Name</th>

                                             <th>Supplier Name</th>

                                             <th>Location</th>

                                             <th>Address</th>

                                             <th>Rating</th>

                                             <th>Image</th>

                                             <th>Status</th>

                                          </tr>

                                       </thead>

                                       <tbody>

                                          <?php

                                             if (! empty ( $newpackage )) {

                                               $i = 1;

                                               foreach ( $newpackage as $key => $package ) {

                                                 ?>

                                          <tr>

                                            <td><?=$i?></td>

                                             <td><input type='checkbox' class='interested<?=$j?>'   id='interested_<?=$j?>_<?=$i?>' onclick="uncheck(<?=$j?>);"  value="<?=$package->package_id?>" /></td>

                                             <td class="center">

                                              <div class="dropdown2" role="group">

                                               <div class="dropdown slct_tbl pull-left sideicbb">

                                                  <i class="fa fa-ellipsis-v"></i>  

                                                    <ul class="dropdown-menu sidedis" style="display: none;">

                                                  <li> <a class="sideicbb1 sidedis" data-placement="top"

                                                title=""

                                                href="<?php echo base_url(); ?>index.php/activity/view_activity_details/<?php echo $package->package_id; ?>/admin"

                                                data-original-title="View Package"> <i

                                                class="fa fa-eye"></i> View

                                                </a></li>

                                                  <li> <a class="sideicbb1 sidedis" data-placement="top"

                                                title=""

                                                href="<?php echo base_url(); ?>index.php/activity/edit_with_price/<?php echo $package->package_id; ?>/admin"

                                                data-original-title="Edit Package"> <i

                                                class="glyphicon glyphicon-pencil"></i> Edit Excursion

                                                </a></li>

                                               <!--  <li> <a class="sideicbb2 sidedis" data-placement="top" title=""

                                                   href="<?php echo base_url(); ?>index.php/activity/edit_itinerary/<?php echo $package->package_id; ?>/admin"

                                                   data-original-title="Edit Itinerary"> <i

                                                   class="glyphicon glyphicon-pencil "></i> Edit Itinerary

                                                </a></li> -->

                                                <!-- <li> <a class="sideicbb3 sidedis" data-placement="top" title=""

                                                   href="<?php echo base_url(); ?>index.php/activity/edit_price/<?php echo $package->package_id; ?>/admin"

                                                   data-original-title="Price Management"> <i

                                                   class="glyphicon glyphicon-pencil "></i> Price Management

                                                </a></li> -->

                                                <!-- <li><a class='sideicbb4 sidedis' data-placement='top'

                                                   title='Change Images'

                                                   href='<?php echo base_url(); ?>index.php/activity/images/<?=$package->package_id;?>/admin'>

                                                <i class='glyphicon glyphicon-th-large'></i> Edit

                                                Images

                                                </a></li> -->

                                                <!-- <li> <a class="sideicbb5 sidedis" data-placement="top" title=""

                                                   href="<?php echo base_url(); ?>index.php/activity/view_enquiries/<?php echo $package->package_id; ?>/admin"

                                                   data-original-title="View Enquiries"><i

                                                   class='glyphicon glyphicon-pencil'></i> View Enquiries

                                                </a></li> -->

                                                <li> <a

                                                   href="<?php echo base_url(); ?>activity/delete_package/<?php echo $package->package_id; ?>"

                                                   data-original-title="Delete"

                                                   onclick="return confirm('Do you want delete this record');"

                                                   class="sideicbb5 sidedis" data-original-title="Delete"> <i

                                                   class="glyphicon glyphicon-trash"></i>Delete Excursion

                                                </a></li>

                                              </ul>

                                            </div>

                                          </div>

                                             </td>

                                             <td><?php echo $package->package_name; ?></td>

                                             <td>

                                          <?php

                                           

                                            echo $package->supplier_name;

                                           ?>

                                                

                                              </td>

                                             <td><?php echo $package->package_city; ?>,<?php $country = $this->Activity_Model->get_country_name($package->package_country); echo $country->name; ?></td>

                                             <td><?php echo $package->address; ?></td>

                                             <td><?php echo $package->rating; ?> Star</td>

                                             <td><a data-id="<?php echo $package->image; ?>" data-toggle="modal" class='openimg' href="#openModalx"> 

                                                <img width="70" height="60" title="<?= $package->package_name; ?>" alt="<?= $package->package_name; ?>"

                                                   src="<?php echo $GLOBALS['CI']->template->domain_upload_acty_images($package->image); ?>"></a>

                                             </td>

                                             <td><?php if ($package->status == '1') { ?>

                                                  <span style="color:green;">Active</span>

                                                  <?php } else { ?>

                                                  <span style="color:red;">In-Active</span>

                                                <?php } ?>

                                             </td>

                                          </tr>

                                          <?php $i++; } } ?>  

                                       </tbody>

                                    </table>

                                 </div>

                              </div>

                           </div>

                        </div>

                     </div>

                  </div>

               </div>

            </div>

         </div>

      </div>

   </div>

   <!-- PANEL BODY END -->

</div>

<!-- PANEL WRAP END -->

</div>

<div class="modal fade " id="openModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

   <div class="modal-dialog modal-lg" role="document">

      <div class="modal-content">

         <div class="modal-header" style='background-color: #f58830;color:white;'>

            <h3 class="modal-title" id="exampleModalLabel">Cropping image</h3>

         </div>

         <div class="modal-body">

            <h4><span style='margin-top:10px;margin-bottom:100px;display:none' id='crp'>Cropped image</span></h4>

            <img id="myImage" class="img-responsive" src="" width='300px' height='300px' alt="">

            <div id="croppieCrop"></div>

         </div>

         <div class="modal-footer">

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>

            <button type="button" class="btn btn-primary" id='upload'>Save image</button>

            <br>

            <span class='btn btn-success' style='margin-top:10px;display:none' id='ress'></span>

         </div>

      </div>

   </div>

</div>

<style>

   /*

   .cr-image

   {

   width:100%;

   height:100%;

   }

   .cr-slider-wrap

   {

   display:none;

   }

</style>

<script>

   var el = document.getElementById('croppieCrop');

   var uploadCrop = new Croppie(el, {

       viewport: { 

         width: 200, 

         height: 200, 

         // points:[50,120,40,130]

         type:'square'

     },

       boundary: { 

         width: 300, 

         height: 300 

       },

       showZoomer: false,

       // enableOrientation: true

   });

   

   var modalThisOpenImage;

   var res_img;

   var myImageSrcs;

   $(document).on("click", ".openimg", function () {

     modalThisOpenImage = $(this);

   });

   $('#openModal').on('shown.bs.modal', function (e) {

     console.log(modalThisOpenImage);

       myImageSrcs = modalThisOpenImage.data('id');

       var base_imgurl='<?php echo IMG_BASEURL;?>extras/custom/keWD7SNXhVwQmNRymfGN/uploads/packages/';

     var myImageSrc = base_imgurl+myImageSrcs;

   

       setTimeout(function(){

       uploadCrop.bind({

           url: myImageSrc,

           // orientation: 4

       });

     }, 500);

     

   });    

   

   $(document).on('click', '#croppieCrop', function (ev) {

     uploadCrop.result({

       type: 'canvas',

       size: 'viewport',

       showZoomer: false,

         // enableOrientation: true,

         enableResize: true

     }).then(function (resp) {

       console.log(resp);

       res_img = resp;

       $('#myImage').show();

       $('#crp').show();

       $('#myImage').attr("src", resp);

     });

   });

   

   

   $('#upload').on('click', function (ev) {

     uploadCrop.result({

       type: 'canvas',

       size: 'original',

       showZoomer: false,

         // enableOrientation: true,

         enableResize: true

     }).then(function (resp) {

       $.ajax({

         url: "<?php echo base_url();?>general/uplod_croppimg",

         type: "POST",

         data: {"image":resp,"file_name":myImageSrcs},

         success: function (data) {

           html = '<img src="' + resp + '" />';

           $("#myImage").html(html);

           $('#ress').show();

           $('#ress').text('Saved Successfully');

         }

       });

     });

        // alert(res_img);

       

   

   });

   

</script>

<script type="text/javascript">

   function activate(that) { window.location.href = that; }

   function uncheck(id){

$('#selectall'+id).prop('checked', false);

   }

   function checkall(id){ 

if($('#selectall'+id).is(':checked')) { 



 $('.interested'+id).prop('checked', true); 

 

} 

else{ 

 $('.interested'+id).prop('checked', false); 

} 

  // for unselect disabled checkbox

   $('.interested'+id+':checked').map( 

  

    function(){ 

      var idd=$(this).attr('id');

      

      if($('#'+idd).is(':disabled')) {

      

      $('#'+idd).prop('checked', false); 

    } 

    }).get(); 



}

function manage_details(id,operation)

{

      var checkval = $('.interested'+id+':checked').map( function(){ return $(this).val();}).get(); 

      if(checkval=='')

      {

        alert('Please Select Any Excursion!!')

        return false;

      }

      if(operation=='delete'){

     var result = confirm("Are you sure to delete?");

      if(result){

          

      }else{

        return false;

      }

    }

              var url="<?php echo base_url().'index.php/activity/manage_activity_details'; ?>" ;

              $.ajax({

                      url :url,

                      type: 'POST',

                      data: {checkval:checkval,operation:operation},

                      success: function(data)

                      {

                        location.reload()

                      }

                    });

}



</script>

<style>

   .external>tbody>tr>td, .external>tbody>tr>th, .external>tfoot>tr>td,

   .external>tfoot>tr>th, .external>thead>tr>td, .external>thead>tr>th {

   padding: 6px;

   }

</style>



