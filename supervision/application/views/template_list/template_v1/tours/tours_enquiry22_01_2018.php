<?php 
foreach($tour_destinations as $td_key => $td_data)
{
 $TOUR_DESTINATIONS[$td_data['id']] =  $td_data['destination'];
}
foreach($tour_list as $tl_key => $tl_data)
{
 $TOUR_LIST[$tl_data['id']]     =  $tl_data['package_name'];
 $TOURS_COUNTRY[$tl_data['id']] =  $tours_country_name[$tl_data['tours_country']];
}
foreach($tours_itinerary as $ti_key => $ti_data)
{
 $TOUR_ITINERARY[$ti_data['id']] =  $ti_data['dep_date'];
}
?>
<style type="text/css">
  .crncy_det .row { margin: 0 -15px; }
</style>
<div id="Package" class="bodyContent col-md-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-title">
				<ul class="nav nav-tabs nav-justified" role="tablist" id="myTab">
					<li role="presentation" class="active" id="add_package_li"><a
						href="#add_package" aria-controls="home" role="tab"
						data-toggle="tab">Tours Inquiry </a></li>
     </ul>
    </div>
   </div>
   <?php /* ?><div class="panel-body">
    <button class="btn btn-primary" type="button" data-toggle="collapse"
    data-target="#advanced_search" aria-expanded="false"
    aria-controls="advanced_search">Advanced Search</button>
    <hr>
    <div class="collapse in" id="advanced_search">
     <form method="GET" autocomplete="off"
     action="<?=base_url().'index.php/tours/tours_enquiry';?>">
     <div class="clearfix form-group">
      <div class="col-xs-4">
       <label> Package Name </label> <input type="text"
       class="form-control" name="package_name"
       value="<?=@$package_name?>" placeholder="Package Name">
      </div>
      <div class="col-xs-4">
       <label> Phone </label> <input type="text" class="form-control"
       name="phone" value="<?=@$phone?>" placeholder="Phone">
      </div> 
      <div class="col-xs-4">
       <label> Email </label> <input type="text" class="form-control"
       name="email" value="<?=@$email?>" placeholder="Email">
      </div>
     </div>
     <div class="col-sm-12 well well-sm">
      <button type="submit" class="btn btn-primary">Search</button>
      <button type="reset" class="btn btn-warning">Reset</button>
     </div>
    </form>
   </div>
  </div><?php */ ?>
  <div class="panel-body">
  </div>
  <div class="table-responsive scroll_main">
   <table class="table table-bordered">
    <thead>
     <tr>
      <th>SN</th>
      <th>Action</th>
      <th>Status</th>  
     <!--  <th>Inquiry By</th>   -->
      <th>Reference No</th>
      <th>Title</th>
      <th>Name</th>
      <th>Phone</th>
      <th>Email</th>
      <th>Package Name</th>
      <th>Inquiry Date</th>
      <th>Departure Date</th>
      
     </tr>
     <thead>
      <tbody>
       <?php
       //debug($tours_enquiry);exit();
       $sn = 1;
       foreach ($tours_enquiry as $key => $data) { 
        $email_btn = '<br><a class="" data-placement="top" href="'.base_url().'index.php/tours/send_link_to_user/'.$data['enquiry_reference_no'].'"
        data-original-title="Send link to user"> <i class="glyphicon glyphicon-th-large"></i></i> Send Link
       </a>';
       $action = '';        
       $module = 'b2c';
       if($module){
        $pax_creationb = car_paxCreation_for_holiday($key);
        $paxhtml = '<div class="modal fade" id="myModal'.$key.'" role="dialog">
        <div class="modal-dialog">

         <!-- Modal content-->
         <div class="modal-content">
          <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal">&times;</button>
           <h4 class="modal-title">Inquiry Details</h4>
          </div>

          <div class="modal-body"><h5 class="modal-title"><b>Passenger Profile</b></h5>';                         

           $paxhtml .= '<span>Title : &nbsp;</span><span>'.get_enum_list ( 'title', $data ['title'] ).'</span><br/>
           <span>First Name : &nbsp;</span><span>'.$data['name'].'</span><br/>
           <span>Last Name : &nbsp;</span><span>'.$data['lname'].'</span><br/>
           <span>Mobile : &nbsp;</span><span>'.$data['pn_country_code'].' '.$data['phone'].'</span><br/>                        
           <span>Email : &nbsp;</span><span>'.$data["email"].'</span><br/><br/>';


           $paxhtml .= '<h5 class="modal-title"><b>Inquiry Details Info</b></h5>
           <span>No of pax : &nbsp;</span><span>'.$data['number_of_passengers'].'</span><br/>

           <span>Duration : &nbsp;</span><span>'.$data['durations'].'</span><br/>';
           if($data["message"]){                   
           $paxhtml .='<span>Message : &nbsp;</span><span>'.$data["message"].'</span><br/><br/>';
           }  
           //agent details
            if($data["agent_remark"]!=""||$data["created_by_name"]!=""){                   
           $paxhtml .='<h5 class="modal-title"><b>Agent Details</b></h5>';
           } 
          if($data["agent_remark"]){                   
           $paxhtml .='<span>Agent Remark : &nbsp;</span><span>'.$data["agent_remark"].'</span><br/>';
           } 
           if($data["created_by_name"]){                   
           $paxhtml .='<span>Agent Name : &nbsp;</span><span>'.$data["created_by_name"].'</span><br/>';
           } 
           

           $paxhtml .= '</div>
           <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           </div>
          </div>


         </div>
        </div>';
        echo $paxhtml;
        $action .= $pax_creationb;
       }


       //Agent remark
       $remarks = '<div class="modal fade remarks_all" id="remarks_'.$data["tours_itinerary_id"].'" role="dialog">
         <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
           <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Agent Remarks</h4>
            <span class="remarks_msg" style="display:none;">
             <div class="alert alert-info" >
             <strong >Agent remarks added successfully.</strong>
            </div>
           </div>
           </span>
           <div class="modal-body">';

           $remarks .='<input type="text" placeholder="Enter Remark" class="form-control" name="agent_remark" id="agent_remark_'.$data["tours_itinerary_id"].'" /
           ><input  type="hidden" name="remark_id" value="'.$data["tours_itinerary_id"].'" id="remark_id_'.$data["tours_itinerary_id"].'" />';
           
            
            $remarks .= '</div>
            <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             <button type="button"  id="current_'.$data["tours_itinerary_id"].'"   class="btn btn-primary remark_submit">Update</button>
             
            </div>
           </div>



          </div>
         </div>';

         echo $remarks;

       if($module == 'b2b'){
        if($created_by_id != 0){
         $this->load->model('custom_db');
         $agency_details = $this->custom_db->get_result_by_query("SELECT * FROM user WHERE user_id=".$created_by_id);
         $agency_details = json_decode(json_encode($agency_details[0]), True);
         $agent_pax_creationb = car_agent_paxCreation ( $key );
         $paxhtml = '<div class="modal fade" id="myModalb2b'.$key.'" role="dialog">
         <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
           <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Agent Profile</h4>
           </div>
           <div class="modal-body">';

            $paxhtml .=  '<span>Agency Name : &nbsp;</span><span>'.ucfirst($agency_details["agency_name"]).'</span><br/>
            <span>Name : &nbsp;</span><span>'.$agency_details["first_name"].'&nbsp;'.$agency_details["last_name"].'</span><br/>
            <span>Mobile : &nbsp;</span><span>+'.$agency_details["country_code"].'&nbsp; - &nbsp;'.$agency_details["office_phone"].'</span><br/>                        
            <span>Email : &nbsp;</span><span>'.$agency_details["email"].'</span><br/>';

            $paxhtml .= '</div>
            <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            
            </div>
           </div>



          </div>
         </div>';
         echo $paxhtml;

         $action .= $agent_pax_creationb;
        }
       }


       if($data['status']==1)
       {
        $status = '<span style="color:green;">Replied</span>';
       }
       else
       {
        $status = '<span style="color:red;">Pending</span>';
       }

       if($data['tours_itinerary_id']!='')
       {
        $dep_date = changeDateFormat($TOUR_ITINERARY[$data['tours_itinerary_id']]);
       }
       else
       {
        $dep_date = '';
       }
       if(!empty($data['tour_id'])){
        $package_str = '<a href="'.base_url().'index.php/tours/voucher/'.$data['tour_id'].'">'.$TOUR_LIST[$data['tour_id']].'</a>';
      }else{
        $package_str = '<span style="color:red;">--</span>';
      }
       
       echo '<tr>
       <td>'.$sn.'</td> ';
       echo '<td class="center">';
       if($data['status']==1)
       {
        echo ' <a href="#" data-toggle="modal" data-target="#remarks_'.$data["tours_itinerary_id"].'"><i class="fa fa-file-o"></i> Pending</a><br>';

       //  echo '<a class="" data-placement="top" href="'.base_url().'index.php/tours/activation_enquiry/'.$data['id'].'/0"
       //  data-original-title="Deactivate Tour Destination"> <i class="glyphicon glyphicon-th-large"></i></i> Pending
       // </a><br>';
      }
      else
      {
        if(!empty($data['tour_id'])){
           $action .= '<br>
        <a href="#" class="approve_modal_btn" data-toggle="modal" data-target="#approve-modal" data-enquiry="'.$data['enquiry_reference_no'].'" data-price="'.$data['price'].'" "><i class="fa fa-file-o"></i> Approve/Quote</a><br>';
       }
       echo '<a class="" data-placement="top" href="'.base_url().'index.php/tours/activation_enquiry/'.$data['id'].'/1"
       data-original-title="Activate Tour Destination"> <i class="glyphicon glyphicon-th-large"></i></i> Replied
      </a><br>';

       
     } 
     echo '<!--<a class="" data-placement="top" href="'.base_url().'index.php/tours/edit_enquiry/'.$data['id'].'"
     data-original-title="Edit Tour Destination"> <i class="glyphicon glyphicon-pencil"></i> Edit
    </a><br>-->
    <a class="callDelete" id="'.$data['id'].'"> 
     <i class="glyphicon glyphicon-trash"></i> Delete</a><br>';
        // $action .= $email_btn;
     echo $action. '</td>';  
     echo '<td>'.$status.'</td>'; 
     //echo '<td>'.$data['created_by'].'</td>'; 
     echo '<td>'.$data['enquiry_reference_no'].'</td>'; 
     echo '<td>'.get_enum_list ( 'title', $data ['title'] ).'</td>
     <td>'.ucfirst($data['name']).'</td>
     <td>'.$data['pn_country_code']." ".$data['phone'].'</td>   
     <td>'.$data['email'].'</td>             
     <td>'.$package_str.'
     </td> 
     <td>'.humanDateFormat_cust($data['date']).'</td>   
     <td>'.humanDateFormat_cust($data['departure_date']).'</td>';   
   
     $sn++;
    }
    ?>
   </tbody>
  </table>
 </div>				
</div>
<div class="modal fade" id="approve-modal" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Approval</h4>
   </div>
   <div class="modal-body">
    <form method="POST" id="approve_form" role="form" action="<?=base_url()?>tours/send_booking_link">
     <input type="hidden" name="enquiry_reference_no" id="enquiry_reference_no" value="">
     <div class="row">
      <div class="col-md-6 mb10">
       <div class="radio form-group inline">
        <label>
         <input type="radio" id="price_type1" name="price_type" value="total" checked="checked">
         Total
        </label>
       </div>&nbsp; &nbsp; &nbsp; 
       <div class="radio form-group inline">
        <label>
         <input type="radio" id="price_type2" name="price_type" value="adult_wise" >
         Adult/Child/Infant wise
        </label>
       </div>
      </div>
      </div>
      <div class="row">
      <div class="col-md-6">
       <div class="form-group">
        <label for="">Adult Count</label>
        <select name="adult_count" id="adult_count" class="form-control" required="required">
         <?=generate_options(custom_numeric_dropdown(20,1,1));?>
        </select>
       </div>
       <div class="form-group">
        <label for="">Child Count</label>
        <select name="child_count" id="child_count" class="form-control" required="required">
         <?=generate_options(custom_numeric_dropdown(5,0,1));?>
        </select>
       </div>
       <div class="form-group">
        <label for="">Infant Count</label>
        <select name="infant_count" id="infant_count" class="form-control" required="required">
         <?=generate_options(custom_numeric_dropdown(5,0,1));?>
        </select>
       </div>
      </div>
      <div class="col-md-6">
       <div class="form-group price_type1">
        <label for="">Price <span style="color:red">*</span></label>
        <input type="text" class="form-control numeric" name="new_price" id="new_price" required="required" value="0">
       </div>
       <div class="form-group price_type2 hide">
        <label for="">Total Adult Price <span style="color:red">*</span></label>
        <input type="text" class="form-control numeric calculation" name="adult_price" id="adult_price" value="0" >
       </div>
       <div class="form-group price_type2 hide">
        <label for="">Total Child Price</label>
        <input type="text" class="form-control numeric calculation" name="child_price" id="child_price" value="0" readonly="readonly">
       </div>  
       <div class="form-group price_type2 hide">
        <label for="">Total Infant Price</label>
        <input type="text" class="form-control numeric calculation" name="infant_price" id="infant_price" value="0" readonly="readonly">
       </div>     
      </div>
      <div class="clearfix"></div>

      <div class="col-md-6">
       <div class="radio form-group inline">
        <label>
         <input type="radio" id="quote_type1" name="quote_type" value="request_quote" checked="checked">
         Request for Quote
        </label>
       </div> &nbsp; &nbsp;
       <div class="radio form-group inline">
        <label>
         <input type="radio" id="quote_type2" name="quote_type" value="final_quote" >
         Final Quote
        </label>
       </div>
      </div>

      <div class="col-md-6 col-xs-12 pull-right crncy_det">
      <div class="row">
      <div class="form-group col-md-6 col-xs-6">
        <label for="">Currency</label>
        <input type="text" id="currency" name="currency" class="form-control" readonly="readonly" value="<?=get_application_currency_preference()?>">
       </div>
       <div class="form-group col-md-6 col-xs-6">
        <label for="">Total</label>
        <input type="text" readonly="readonly" class="form-control" id="total" name="total" value="0">
       </div>
       <div class="form-group col-md-12 col-xs-12">
        <label for="">&nbsp;</label>
        <button class="btn btn-primary pull-right">Send</button>
       </div>       
      </div>   
      </div>
     </div>
    </form>
   </div>
<!--    <div class="modal-footer">
 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div> -->
  </div>
 </div>
</div>
</div>
<script type="text/javascript">
//function add_remark(id){

  //alert(id);
 // $.ajax({
 //      type: 'GET',
 //      url: app_base_url+'index.php/ajax/car_list/'+offset+'?booking_source=<?=$car_booking_source[0]['source_id']?>&search_id=<?=$car_search_params['search_id']?>&op=load'+url_filters,
 //      async: true,
 //      cache: true,
 //      //dataType: 'json',
 //      success: function(res) {
 //        loader(res);
 //        $(".car_filter_load").hide();
 //        $("#result_found_text").removeClass('hide');
 //      }
 //    });
//}
 $(document).ready(function()
 {
  
   $('.remark_submit').click(function(){
     var remark = this.id.split("_");
     var r_id = remark[1];
     var remark_text_id = "#agent_remark_"+r_id;
     var remark_text = $(remark_text_id).val();
     $.ajax({
            url:app_base_url+'tours/add_agent_remark',
            type:'POST',
            data:{'r_id':r_id,'agent_remark':remark_text},
            dataType: "json",
            success:function(ret){
            if(ret==true){
             
             setTimeout(function(){
             $('.remarks_msg').show();
             window.location.reload();
              }, 1000);
             } },
            error:function(){
            }
           }) ;
  });

  

  $('#child_count').change(function(){
    if($(this).val()>0){
      $('#child_price').removeAttr('readonly');
    }else{
      $('#child_price').attr('readonly','readonly');
    }
  });
  $('#infant_count').change(function(){
    if($(this).val()>0){
      $('#infant_price').removeAttr('readonly');
    }else{
      $('#infant_price').attr('readonly','readonly');
    }
  });
  $('#approve_form').submit(function() {
   if($('#total').val() == 'NaN' || $('#total').val() == 0){
    $('#total').addClass('invalid-ip');
    return false;
   }
  });
  $('#price_type1').click(function(){
    $(this).prop('checked', true);
    $('#price_type2').prop('checked', false);
    $('.price_type1').removeClass('hide');
    $('.price_type2').addClass('hide');
    $('#total').val(parseFloat($('#new_price').val()));
  });
  $('#price_type2').click(function(){
    $(this).prop('checked', true);    
    $('#price_type1').prop('checked', false);    
    $('.price_type2').removeClass('hide');
    $('.price_type1').addClass('hide');
    var total = 0;
    $.each($('.calculation'), function() {
     if($(this).val() == ''){
      var price = 0;
     }else{
      var price = $(this).val();     
     }
     total = total + parseFloat(price);
    });
    $('#total').val(total);
  });
  $('.approve_modal_btn').click(function(){
   $('#enquiry_reference_no').val($(this).data('enquiry'));
   var price = $(this).data('price');
   if(price == ''){
    price =0;
   }  
   $('#new_price').val(price);
   $('#total').val(price);
  });
  $('#new_price').on('keyup blur change', function(e) {
    $('#total').val($(this).val());
  });
  $('.calculation').on('keyup blur change', function(e) {
   var total = 0;
   $.each($('.calculation'), function() {
    if($(this).val() == ''){
     var price = 0;
    }else{
     var price = $(this).val();     
    }
     total = total + parseFloat(price);
   });
   $('#total').val(total.toFixed(2));
  });

  $(".callDelete").click(function() { 
   $id = $(this).attr('id'); 
   $response = confirm("Are you sure to delete this record???");
   if($response==true){ window.location='<?=base_url()?>index.php/tours/delete_enquiry/'+$id; } else{}
  });
 });
</script>
<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" src="http://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script> $(function () { $('.table').DataTable(); }); </script> 
<?php 
function send_link_to_user($enquiry_reference_no,$tour_id)
{

 return '<a data-toggle="modal" data-target="#send_link_to_user" id="send_link_to_user_id" class="send_link_to_user_class fa fa-envelope-o" data-enquiry_reference_no="'.$enquiry_reference_no.'" data-tour_id="'.$tour_id.'"> Send Link</a>';
}
?>