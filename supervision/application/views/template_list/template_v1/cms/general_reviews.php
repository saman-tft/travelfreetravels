<?php 
error_reporting(0);


//debug($TOUR_DESTINATIONS);debug($TOUR_LIST);debug($TOUR_ITINERARY);
?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY ?>/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo SYSTEM_RESOURCE_LIBRARY ?>/datatables/dataTables.bootstrap.min.js"></script>
<script> $(function () { $('.table').DataTable(); }); </script>
<div id="Package" class="bodyContent col-md-12">
  <div class="panel panel-default">
    <!-- PANEL WRAP START -->
    <div class="panel-heading">
      <!-- PANEL HEAD START -->
      <div class="panel-title">
        <ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
          <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
          <li role="presentation" class="active" id="add_package_li"><a
            href="#add_package" aria-controls="home" role="tab"
            data-toggle="tab">General Reviews </a></li>
        </ul>
      </div>
    </div>
    <!-- PANEL HEAD START -->
    <div class="panel-body">
      <!-- PANEL BODY START -->     
    </div>
    <!-- PANEL BODY END -->
  
  <!-- PANEL WRAP END -->
  
      <div class="table-responsive scroll_main">
      <table class="table table-bordered">
      <thead>
        <tr>
          <th>S.No</th>
          <th>Name</th>
          <th>Country Code</th>
          <th>Phone</th>
          <th>Email</th>
        <!--   <th>Country</th> -->
          <th>Module Name</th>
          <th>Posted On</th>
          <th>Review Comments</th>
          <th>Publish Status</th>
         <!--  <th>Status</th> -->
          <th>Action</th>       
        </tr>
      <thead>
      <tbody>
        <?php
        $sn = 1;
        //debug($TOUR_ITINERARY); 

        foreach ($reviews as $key => $data) { 
        if($data['status']==1)
        {
           $status = '<span style="color:green;">Replied</span>';
        }
        else
        {
           $status = '<span style="color:red;">Pending</span>';
        }

               $dep_dates_list = ''; 
               $home_status_list="";
               $ij = 0; 
               
               
              //debug($data);exit;
                
                 
                  if($data['status'] == 1)
                  {
                    $checked = 'checked';
                  }
                  else
                  {
                    $checked ='';
                  }
               
              
             
                $ddl = '';
                $rand = '';
                $ddl_data = '';
                $dep_dates_list .= 'Publish: <input type="checkbox" class="published_status" id="published_status'.$tour_id.'" value="1" data-tourid="'.$data['origin'].'" data-depdate="'.$ddl_data.'" '.$checked.'><br>';
             

       /* if($data['tours_itinerary_id']!='')
        {
            $dep_date = changeDateFormat($TOUR_ITINERARY[$data['tours_itinerary_id']]);
        }
        else
        {
            $dep_date = '';
        }*/
        $dep_date = changeDateFormat($data['created']);
        echo '<tr>
              <td>'.$sn.'</td>
              <td>'.ucfirst($data['user_name']).' '.ucfirst($data['user_lname']).'</td>
              <td>'.substr($data['pn_country_code'], -3).'</td>
              <td>'.$data['user_mobile'].'</td>
              <td>'.$data['user_email'].'</td>
           
              <td>'.$data['module'].'</td> 
              <td>'.$dep_date.'</td>                
              <td><a href="javascript:void(0);" data-toggle="modal" data-target="#myModal_'.$sn.'" class="portnmeter">Read Message</a></td>
              ';
        echo '
        <td>'.$dep_dates_list.'</td>
        <td class="center">';
      /*  if($data['status']==1)
        {
        echo '<a class="" data-placement="top" href="'.base_url().'index.php/tours/activation_review/'.$data['origin'].'/0"
              data-original-title="Deactivate Review"> <i class="glyphicon glyphicon-th-large"></i></i> Pending
              </a>';
        }
        else
        {
        echo '<a class="" data-placement="top" href="'.base_url().'index.php/tours/activation_review/'.$data['origin'].'/1"
              data-original-title="Activate Review"> <i class="glyphicon glyphicon-th-large"></i></i> Replied
              </a>';
        } */
        echo '
              <a class="callDelete" id="'.$data['origin'].'"> 
              <i class="glyphicon glyphicon-trash"></i> Delete</a>';
        echo '</td>';

        $message=$data['comment'];
          echo '<div class="modal fade holidayenquiry in" id="myModal_'.$sn.'" role="dialog" aria-hidden="false" style="padding-right: 17px;">
                   <div class="modal-dialog">
                      <div class="modal-content">
                         <div class="modal-header" style="">
                            <button type="button" class="close" data-dismiss="modal">×</button>           
                            <h4 class="modal-title" style="color: #000;">Message</h4>
                         </div>
                         <div class="modal-body">
                            <div class="row">
                               <div class="col-md-12">
                                 <p>'.$message.'</p>
                               </div>
                            </div>
                            <hr>
                         </div>
                      </div>
                   </div>
                </div>';

        $sn++;
        }
        ?>
    </tbody>
    </table>
    </div>        
    </div>
    </div>
    <script type="text/javascript">  
    $(document).ready(function()
    {
           
          $(".callDelete").click(function() { 
            $id = $(this).attr('id'); //alert($id);
            $response = confirm("Are you sure to delete this record???");
            if($response==true){ window.location='<?=base_url()?>index.php/cms/delete_general_review/'+$id; } else{}
           });


          $(document).on("change",".published_status",function()
        {
          var id_X = $(this).attr('id');
          if($(this).is(":checked"))
          {
           var publish_status = 1;
         }
         else
         {
           var publish_status = 0;
         } 
         var id  = $(this).data('tourid');
         //var dep_date = $(this).data('depdate');
         $.ajax({
          url: '<?php echo base_url();?>index.php/cms/ajax_tour_publish/',
          method: 'post',
          data: {'id':id,'publish_status':publish_status},
          dataType: 'json',
          success:function(data){
            alert(data);
          //   $(".alert").hide();                
          //   var html = '';
          //   if(publish_status ==1)
          //   {
          //    for (x in data['first']) {
          //     html += data['first'][x]+'\n';
          //   }
          // }
          // for (x in data['sec']) {
          //   html += data['sec'][x]+'\n';
          // }
          // //alert(data);   
          // if(data['first'].length){
          //   $("#"+id_X).prop("checked",false);
          // }            
        }             
      });
       });











    });
        </script>
<!--
<script src="/chariot/extras/system/template_list/template_v1/javascript/js/nicEdit/nicEdit.js"></script>
<script type="text/javascript" src="/chariot/extras/system/template_list/template_v1/javascript/js/nicEdit/nicEdit_call.js"></script>

<link rel="stylesheet" href="/chariot/extras/system/template_list/template_v1/javascript/js/datatables/tables.css">
<script src="/chariot/extras/system/template_list/template_v1/javascript/js/datatables/jquery.dataTables.js"></script>
<script src="/chariot/extras/system/template_list/template_v1/javascript/js/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
            $(document).ready(function() {
                $('.table').dataTable();  
            });
</script>
-->
