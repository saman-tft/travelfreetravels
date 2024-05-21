<?php

if(!empty($product_list)){
?>

<table class="table table-condensed table-bordered scroll_main_set datatable" id="hotel">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Action</th>
                  <th>Amount Range</th>
                  <th>Reward Point</th>
                  
                  
                  
                  
                  <!--<th>Current Status</th> -->
                </tr>
              </thead>  
              <tbody>
                <?php

                // debug($query);exit;
                $action="";
                $i=1;
                foreach ($product_list as $key => $value) {
                  
                 $action="<button type='button' class='btn btn-info btn-lg' onclick='deletefun(".$value['id'].")'>delete</button>";
                ?>
                <tr id="col_id_<?=$value['id']?>">
                  <td><?=$i?></td>
                  <td><?=$action?></td>
                  <td><?=$value['start_range']?>-<?=$value['end_range']?></td>
                  <td><?=$value['point']?></td>
                
              
              
            </tr>
            <?php 
              $i++;
          } ?>
              </tbody>            
              <tfoot>
                <tr>
                  <th>S.No</th>
                  <th>Action</th>
                  <th>Amount Range</th>
                  <th>Reward Point</th>

                  
                  
                  
                </tr>
              </tfoot>
            </table>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">

function deletefun(id){
  
    // alert();
    
    
 
   
  $status=$.post(app_base_url + "index.php/loyalty_program/delete_module_range", {id: id}, function(result){

        $('#col_id_'+id).remove();
    });
    if($status!='')
    {
     toastr.info("deleted Successfully!!!");
     // window.location.reload();
    }
    else
    {
     toastr.info("Not Update!!!");
     // window.location.reload();
    }
}  



</script>


<?php } ?>