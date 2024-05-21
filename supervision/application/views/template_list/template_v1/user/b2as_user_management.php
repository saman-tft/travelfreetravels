 <?php 
    $this->CI = &get_instance ();
    $condition ['supplier_id'] = $form_data['user_id'];
     $supplier_crs_privilage = $this->CI->db->get_where('supplier_crs_privilage', $condition )->row_array ();
     $privillages=$supplier_crs_privilage['supplier_privailage'];
    // debug($privillages);exit();
 ?>

   <script>

        var privillages="<?php echo $privillages;?>";

         $(document).ready(function() {            
         //var res = privillages.split(",");
         var res = privillages.split("");
            console.log(res);

            if(res.includes("1"))
           {
                $("#b2as_useruser_type1").attr("checked",true);
           }

           if(res.includes("2"))
           {
                $("#b2as_useruser_type2").attr("checked",true);
           }


            if(res.includes("3"))
           {
                $("#b2as_useruser_type3").attr("checked",true);
           }

            if(res.includes("4"))
           {
                $("#b2as_useruser_type4").attr("checked",true);
           }

            
         });



    $(document).ready(function() {
        
        $(".user_type").click(function()
        {
            check_crs_type();            
        });
        check_crs_type();  
    });

    function check_crs_type()
        {
            var crs_len=$('[name="user_type[]"]:checked').length;

            if(crs_len>0)
            {
                $('.user_type').attr('required',false);
            }else
            {
                 $('.user_type').attr('required',true);
            }
        }
</script>

    <?php


     if (intval(@$eid) > 0) 
     {
        if (form_visible_operation()) {
            $tab1 = "active";
            $tab2 = "";
        } else {
            $tab2 = "active";
            $tab1 = "";
        }              
     }
     else
     {
        if (form_visible_operation()) 
        {
                $tab1 = "";
                $tab2 = "active";
            } else {
                $tab2 = "";
                $tab1 = "active";
            }
     }
 



// debug($tab1);
// debug($tab2);
// exit();
$_datepicker = array(
    array(
        'created_datetime_from',
        PAST_DATE
    ),
    array(
        'created_datetime_to',
        PAST_DATE
    )
);
$this->current_page->set_datepicker($_datepicker);
$tmplate_img_dir = $this->template->template_images();
if (is_array($search_params)) {
    extract($search_params);
}
?>
<style>
    .radio{
width: 100%;
    float: left;
    margin-bottom: 20px;}
</style>
<!-- HTML BEGIN -->
<div id="general_user" class="bodyContent">
    <div class="panel panel-default">
        <!-- PANEL WRAP START -->
        <div class="panel-heading">
            <!-- PANEL HEAD START -->
            <div class="panel-title">
                <?php
                if (intval(@$eid) > 0) {
                    $i_fil = '';
                    if (@$_GET ['user_status']) {
                        $i_fil .= 'user_status=' . intval($_GET ['user_status']);
                    }
                    $cancel_edit_btn = '<a class="btn btn-sm btn-danger pull-right" href="' . base_url() . 'index.php/user/b2as_user?' . $i_fil . '"><i class="fa fa-trash"></i> Click here to Cancel Editing</a>';
                } else {
                    $cancel_edit_btn = '';
                }
               // echo $cancel_edit_btn;
                ?>
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <input type="hidden" name="base_url" id="base_url" value="<?php echo base_url('index.php/user/b2as_user?user_status=' . (isset($_GET['user_status']) == true ? empty($_GET['user_status']) == true ? '0' : '1'  : '')); ?>"/>
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE START-->
                    <li role="presentation" class="<?php echo $tab1; ?>"><a
                            id="fromListHead" href="#fromList" aria-controls="home" role="tab"
                            data-toggle="tab"> <i class="fa fa-edit"></i> <?php
                if ($_GET ['creation_source'] = "dist") {
                    echo "Create/Update Supplier";
                } else {
                    get_app_message('AL00337');
                }
                ?>
                        </a></li>
                    <li role="presentation" class="<?php echo $tab2; ?>"><a
                            href="#tableList" aria-controls="profile" role="tab"
                            data-toggle="tab"> <i class="fa fa-users"></i> <?= (isset($_GET['user_status']) == true ? empty($_GET['user_status']) == true ? 'Inactive' : 'Active'  : '') ?> Supplier List</a>
                    </li>
                    <!-- INCLUDE TAB FOR ALL THE DETAILS ON THE PAGE END -->
                </ul>
            </div>
        </div>
        <!-- PANEL HEAD START -->
        <div class="panel-body">
            <!-- PANEL BODY START -->
            <div class="tab-content">
                <div role="tabpanel" class="clearfix tab-pane <?php echo $tab1; ?>" id="fromList">
                    <div class="clearfix">
<?php if (isset($eid) == true && empty($eid) == false) { ?>
                            <?php //debug($form_data);exit;  ?>
                            <div class="col-md-2">
                            <?php //  $GLOBALS['CI']->template->isolated_view('user/agent_menu.php', $form_data) ?>           
                            </div>
                            <?php } ?>
                        <div class="col-md-8">
                        <?php
                        /**
                         * ********************** GENERATE CURRENT PAGE FORM ***********************
                         */
                        $form_data ['user_type'] = SUPPLIER;

                        if (isset($eid) == false || empty($eid) == true) {
                            /**
                             * * GENERATE ADD PAGE FORM **
                             */
                            
                            $form_data ['country_code'] = (isset($form_data ['country_code']) == false ? '+60' : $form_data ['country_code']);
                            
                            $form_data ['country_name'] = (isset($form_data ['country_name']) == false ? Malaysia : $form_data ['country_name']);
$form_data ['country_code']="+1";
                            $temp_record = $this->custom_db->single_table_records('domain_list', '*');
                            $form_data['active_data'] =$temp_record['data'][0];
                            
                            $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
                            $form_data['phone_code'] =$temp_record['data'];

                            $city_record = $this->custom_db->single_table_records('api_city_list', 'destination',array('country'=>$form_data['active_data']['api_country_list_fk']));
                            $form_data['city_list'] =$city_record['data'][0];

                            $form_data['country_code_list'] = $this->db_cache_api->get_country_code_list();
                            $country_code = $this->db_cache_api->get_country_code_list_profile();
                            $phone_code_array = array();
                            foreach($country_code['data'] as $c_key => $c_value){
                                // $phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
                                $phone_code_array[$c_value['country_code']] = $c_value['name'].' '.$c_value['country_code'];
                                // debug($phone_code_array);exit;
                                
                            }
                            // debug($phone_code_array);exit;
                            $form_data['phone_code_array'] = $phone_code_array;

                            $form_data['country_list'] = $this->db_cache_api->get_country_list();
                            $form_data['state_list'] = $this->db_cache_api->get_state_list();
                            // debug($form_data);exit;
                            // echo $this->current_page->generate_form('b2as_user', $form_data);
                            echo $this->template->isolated_view('user/supplier_register',$form_data);
                        } else {
                            $form_data ['country_name'];
                            $form_data['email'] = provab_decrypt($form_data['email']);
                            $form_data['user_name'] = provab_decrypt($form_data['user_name']);

                           if($form_data ['country_code'] == 92 ){
                            $form_data ['country_code']=91;} 

                            if($form_data['city'] > 0){
                             $form_data['city_name'] = get_city_name($form_data['city']);
                            }
                            
$form_data ['country_code']='+91';

                            // debug($form_data);exit;
                            // echo $form_data ['country_code'];die;
                            // debug($supplier_crs_privilage);die;
                             $temp_record = $this->custom_db->single_table_records('domain_list', '*');
                            $form_data['active_data'] =$temp_record['data'][0];
                            
                            $temp_record = $this->custom_db->single_table_records('api_country_list', '*');
                            $form_data['phone_code'] =$temp_record['data'];

                            $city_record = $this->custom_db->single_table_records('api_city_list', 'destination',array('country'=>$form_data['active_data']['api_country_list_fk']));
                            $form_data['city_list'] =$city_record['data'][0];

                            $form_data['country_code_list'] = $this->db_cache_api->get_country_code_list();
                            $country_code = $this->db_cache_api->get_country_code_list_profile();
                            $phone_code_array = array();
                            foreach($country_code['data'] as $c_key => $c_value){
                                // $phone_code_array[$c_value['origin']] = $c_value['name'].' '.$c_value['country_code'];
                                $phone_code_array[$c_value['country_code']] = $c_value['name'].' '.$c_value['country_code'];
                                // debug($phone_code_array);exit;
                                
                            }
                            // debug($phone_code_array);exit;
                            $form_data['phone_code_array'] = $phone_code_array;

                            $form_data['country_list'] = $this->db_cache_api->get_country_list();
                            $form_data['state_list'] = $this->db_cache_api->get_state_list();
                             // debug($form_data);exit;
                            echo $this->template->isolated_view('user/supplier_register',$form_data);
                            // echo $this->current_page->generate_form('b2as_user_edit', $form_data);
                        } 
                        /**
                         * ********************** GENERATE UPDATE PAGE FORM ***********************
                         */
                        ?></div>
                    </div>
                </div>
                <div role="tabpanel" class="clearfix tab-pane <?php echo $tab2; ?>"
                     id="tableList">
                    <!--/************************ GENERATE Filter Form ************************/-->
                    <!--  <h4>Search Panel</h4>
                            <hr>
                             <form method="GET" autocomplete="off">
                                    <input type="hidden" name="user_status" value="<?= @$user_status ?>">
                                    <div class="clearfix form-group">
                                            <div class="col-xs-4">
                                                    <label>Agency Name</label> <input type="text"
                                                            placeholder="Agency Name" value="<?= @$agency_name ?>"
                                                            name="agency_name" class="form-control">
                                            </div>
                                            <div class="col-xs-4">
                                                    <label>Agency ID</label> <input type="text"
                                                            placeholder="Agency ID" value="<?= @$uuid ?>" name="uuid"
                                                            class="form-control">
                                            </div>
                                            <div class="col-xs-4">
                                                    <label>PAN</label> <input type="text" placeholder="PAN"
                                                            value="<?= @$pan_number ?>" name="pan_number"
                                                            class="form-control">
                                            </div>
                                            <div class="col-xs-4">
                                                    <label>Email</label> <input type="text" placeholder="Email"
                                                            value="<?= @$email ?>" name="email" class="form-control">
                                            </div>
                                            <div class="col-xs-4">
                                                    <label>Phone</label> <input type="text"
                                                            placeholder="Phone Number" value="<?= @$phone ?>" name="phone"
                                                            class="numeric form-control">
                                            </div>
                                            <div class="col-xs-4">
                                                    <label>Member Since</label> <input type="text"
                                                            placeholder="Registration Date" readonly
                                                            value="<?= @$created_datetime_from ?>" id="created_datetime_from"
                                                            name="created_datetime_from" class="form-control">
                                            </div>
                                            <div class="col-xs-4">
                                                    <label>Group</label> <select value="<?= @$group_fk ?>"
                                                            name="group_fk" class="form-control">
                                                            <option value="">Select Agent Group</option>
<?= generate_options($group_list, array(@$group_fk)) ?>
                                                    </select>
                                            </div>
                                            <div class="col-xs-4">
                                                    <label>Reporting To</label> <select value="<?= @$group_fk ?>"
                                                            name="reporting_to_id" class="form-control">
                                                            <option value="">Select</option>
                                                            <option value="0">Admin</option>
<?= generate_options($dist_list, array(@$reporting_to_id)) ?>
                                                    </select>
                                            </div>
                                    </div>
                                    <div class="col-sm-12 well well-sm">
                                            <button class="btn btn-primary" type="submit">Search</button>
                                            <button class="btn btn-warning" type="reset">Reset</button>
                                    </div>
                            </form> -->
                    <div class="clearfix"></div>
                    <!--/************************ GENERATE Filter Form ************************/-->
                    <div class="clearfix">
                        <div class="row">
                            <!-- <a href="<?php echo base_url(); ?>index.php/user/b2as_user/excel<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>">
                                <button class="btn btn-primary btn-xs" type="button">Export to Excel</button>
                            </a> -->
                          <!--   <a href="<?php echo base_url(); ?>index.php/user/b2as_user/pdf<?= !empty($_SERVER["QUERY_STRING"]) ? '?' . $_SERVER["QUERY_STRING"] : '' ?>" target="_blank">
                                <button class="btn btn-primary btn-xs" type="button">Pdf</button>
                            </a> -->

                            <!-- <button class="btn btn-primary btn-xs" type="button" onclick="window.print(); return true;">Print</button> -->

                        </div>
                        <!--/************************ GENERATE Filter Form ************************/-->
<!--<h4>Search Panel</h4>
<button class="btn btn-info" id="clear_search_filters">Clear Search Filter <i class="fa fa-close"></i></button>
<hr>
<form method="GET" autocomplete="off" id="search_filter_form">
    <input type="hidden" name="user_status" value="<?=@$user_status?>" >
    <div class="clearfix form-group">
        <div class="col-xs-4">
         <label>Service</label>
            <input type="text" placeholder="Service" value="<?=@$service_type?>" name="service_type" class="search_filter form-control">
   
        <select name="service_type" id="service_type" class="search_filter form-control">
          <option value="">All</option>  
          <option value="tour_crs" <?php if($_GET["service_type"]=="tour_crs"){  echo "selected"; }?>>Tour CRS</option>
          <option value="villas_apts" <?php if($_GET["service_type"]=="villas_apts"){  echo "selected"; }?>>Villas & Apts/Hotel</option>
          <option value="private_transfer" <?php if($_GET["service_type"]=="private_transfer"){  echo "selected"; }?>>Private Transfer</option>
          <option value="private_car" <?php if($_GET["service_type"]=="private_car"){  echo "selected"; }?>>Private Car</option>
          <option value="private_jet" <?php if($_GET["service_type"]=="private_jet"){  echo "selected"; }?>>Private Jet</option>
        </select>
        </div>
    </div>
    <div class="col-sm-12 well well-sm">
        <button class="btn btn-primary" type="submit">Search</button> 
    <button class="btn btn-warning" id="reset_value" type="reset">Reset</button> 
    </div>
</form>-->
<div class="clearfix"></div>
<!-- NEW LINES FOR USER EXPORT BUTTONS -->
<a href="<?php echo base_url(); ?>index.php/user/user_export/?op=csv&q=<?php echo SUPPLIER; ?>&user_status=<?php echo @$user_status;?>" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i> Export users csv</a>
        <a href="<?php echo base_url(); ?>index.php/user/user_export/?op=pdf&q=<?php echo SUPPLIER; ?>&user_status=<?php echo @$user_status;?>" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i> Export users pdf</a>
        <br />
        <br />
<!--/************************ GENERATE Filter Form ************************/-->
<?php
/**
 * ********************** GENERATE CURRENT PAGE TABLE ***********************
 */

echo get_table(@$table_data, $total_rows);
/**
 * ********************** GENERATE CURRENT PAGE TABLE ***********************
 */
?>
                    </div>
                </div>
            </div>
        </div>
        <!-- PANEL BODY END -->
    </div>
    <!-- PANEL WRAP END -->
</div>
<!-- HTML END -->
<div class="modal fade" id="irctc_details" tabindex="-1" role="dialog"
     aria-labelledby="irctcDetails">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="irctcDetailsLabel">Agent IRCTC Details</h4>
            </div>
            <div class="modal-body">
                <div class="loader">
                    <img src="<?= $tmplate_img_dir ?>loader_v3.gif" alt="Loader">
                </div>
                <div id="irctc_data"></div>
            </div>
        </div>
    </div>
</div>
<?php

function get_table($table_data = '', $total_rows = 0) {
    $user_status = isset($_GET ['user_status']) ? $_GET ['user_status'] : '';
    $st = isset($_GET ['st']) ? $_GET ['st'] : '';
    // debug( $table_data);die;
    $table = '<div class="row">';
    $pagination = '<div class="col-md-7">' . $GLOBALS ['CI']->pagination->create_links() . '<span class="">Total ' . $total_rows . ' Suppliers</span></div>';
    $table .= $pagination;
   /* $table .= '<div class="col-md-5">' . advanced_search(array(
                'text' => $st,
                'status' => $user_status
            )) . '</div>';*/
    $table .= '</div>
   <div class="clearfix table-responsive">
   <table id="table" class="table table-condensed table-bordered">';
    $table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> ' . get_app_message('AL006') . '</th>
   <th>Action</th>
   <th>Status</th>
   
   <th>Supplier Name</th>' .
            (check_user_previlege('p53') ? '
  ' : '') .
            '<th>Phone</th>
   <th>Email</th>
   <th>Country</th>
    
    <th>PIN </th>
   <th width="50px">Address</th>
   
   
   </tr></thead><tbody>';

    // echo "<pre>"; print_r($table_data); echo "</pre>"; die();
   $statename = '';
    if (valid_array($table_data) == true) {
        $template = new Template();
        //echo $path = $template->domain_pan_upload_path ();
        $segment_3 = $GLOBALS ['CI']->uri->segment(3);
        $current_record = (empty($segment_3) ? 0 : $segment_3);
        $rep_url = base_url() . 'index.php/report/';
        $dep_url = base_url() . 'index.php/management/b2b_balance_manager';
        $panlink = '';
        $gstlink = '';
        foreach ($table_data as $k => $v) {
            //echo provab_decrypt($v ['email']);
            /*
             * $last_login = 'Last Login : '.last_login($v['last_login']);
             * $login_status = login_status($v['logout_date_time']);
             */

            $service = explode(',',$v['service_type']);
             $service_type_dis = array();
            // debug($service);exit;
            foreach($service as $key_ser => $service_type){
                //debug($service_type);exit;
            
                if($service_type == 'villas_apts'){
                    $service_type_dis[] = 'Villas & Apts/Hotel';
                }
                 if($service_type == 'private_transfer'){
                     $service_type_dis[] = 'Private Transfer';
                }
                 if($service_type == 'tour_crs'){
                     $service_type_dis[] = 'Tour CRS';
                }
                 if($service_type == 'private_car'){
                     $service_type_dis[] = 'Private Car';
                }
                if($service_type == 'private_jet'){
                    $service_type_dis[] = 'Private Jet'; 
                }

            }
            
//debug($service_type);exit;



             if($v['state'] > 0){
            $statename = get_state_name($v['state']);
            }
            else{

                $statename = $v['state_txt'];
            }
            $file_path = $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template);
            if($v ['panimage'] != ''){
               // debug('wise_travel_new'.DOMAIN_PAN_UPLOAD_DIR.$v ['panimage']);exit;
                //wise_travel_new/extras/custom/TMX1512291534825461/images/IMG-compid1583998138.jpeg
                $CI=& get_instance();
               // debug(RESOURCE_DIR);exit();
                 //debug($CI->template->domain_image_full_path($v ['panimage']));exit();
                $panlink = '<a target="_blank" href='.$file_path.$v ['panimage'].' >View File</a>';
            }else{
                $panlink = "No File Uploaded.";

            }
           // debug($panlink);exit;
            if($v ['gstimage'] != ''){

                // $gstlink = '<a target="_blank" href='.DOMAIN_PAN_UPLOAD_DIR.$v ['gstimage'].' >View File</a>';
                $gstlink = '<a target="_blank" href='.$file_path.$v ['gstimage'].' >View File</a>';
            }else{
                $gstlink = "No File Uploaded.";

            }

            
            //debug($v);exit;
            if($v ['country_name'] == ''){
                $v ['country_name'] = 'Malaysia';
            }
            $dep_req = '';
            if (isset($v ['dep_req']) == true && isset($v ['dep_req'] ['pending']) == true) {
                $dep_req = intval($v ['dep_req'] ['pending'] ['count']);
            } else {
                $dep_req = 0;
            }

            $booking_summ = '';
            if (is_active_airline_module()) {
                $booking_summ_flight = intval(@$v ['booking_summ'] ['flight'] ['BOOKING_CONFIRMED'] ['count']);
            }

            if (is_active_airline_module()) {
                $booking_summ_hotel = intval(@$v ['booking_summ'] ['hotel'] ['BOOKING_CONFIRMED'] ['count']);
            }

            if (is_active_airline_module()) {
                $booking_summ_bus = intval(@$v ['booking_summ'] ['bus'] ['BOOKING_CONFIRMED'] ['count']);
            }

            $booking_summ = $booking_summ_flight + $booking_summ_hotel + $booking_summ_bus;

            // Booking
            //error_reporting(E_ALL);
            $table .= '<tr>
      <td>' . (++$current_record) . ' </td>
       <td>' . get_user_actions($v) . '</td>
       
      <td>' . get_status_toggle_button($v ['status'], $v ['user_id'], $v ['uuid']) . '</td> 
    
     
      
      <td>' . $v ['first_name'] . ' ' . $v ['last_name'] . '</td>'
                    . (check_user_previlege('p53') ? '
     ' : '') .
                    '<td>' . $v ['phone'] . '</td>
      <td>' . provab_decrypt($v ['email']) . '</td>
      <td>' . $v ['country_name'] . '</td>
      
      <td>' . $v ['pin_code'] . ' </td>
      <td>' . $v ['address'] . '</td> 
 
       
      
      </tr>';
        }
    } else {
        $table .= '<tr><td colspan="9">' . get_app_message('AL005') . '</td></tr>';
    }
    $table .= '</tbody></table></div>';
    return $table;
}

function get_status_label($status) {
    if (intval($status) == ACTIVE) {
        return '<span class="label label-success"><i class="fa fa-circle-o"></i> ' . get_enum_list('status', ACTIVE) . '</span>
  <a role="button" href="" class="hide">' . get_app_message('AL0021') . '</a>';
    } else {
        return '<span class="label label-danger"><i class="fa fa-circle-o"></i> ' . get_enum_list('status', INACTIVE) . '</span>
    <a role="button" href="" class="hide">' . get_app_message('AL0020') . '</a>';
    }
}

function get_status_toggle_button($status, $user_id, $uuid) {
    $status_options = get_enum_list('status');

    // debug(array($status=>
    //             $status
    //         ));
    // exit();
    // return '<select autocomplete="off" class="toggle-user-status" data-user-id="' . $user_id . '" data-uuid="' . $uuid . '">' . generate_options($status_options, array($status=>
    //             $status
    //         )) . '</select>';
    /*
     * if (intval($status) == INACTIVE) {
     * return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
     * } else {
     * return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
     * }
     */


    $select = '<select autocomplete="off" class="toggle-user-status" data-user-id="'.$user_id.'" data-uuid="'.$uuid.'">';
    foreach ($status_options as $key => $value) {
        $active=($key==$status)?'selected':'';
        
    $select.='<option value="'.$key.'" '.$active.'>'.$value.'</option>';
    }
    $select.='</select>';
    return $select;
}

function get_irctc_status_toggle_button($status, $user_id, $uuid) {
    $status_options = get_enum_list('status');
    return '<select autocomplete="off" class="toggle-irctc-user-status" data-user-id="' . $user_id . '" data-status="' . $status . '">' . generate_options($status_options, array(
                $status
            )) . '</select>';
    /*
     * if (intval($status) == INACTIVE) {
     * return '<a role="button" href="'.base_url().'user/activate_account/'.$user_id.'/'.$uuid.'" class="text-success">Activate</a>';
     * } else {
     * return '<a role="button" href="'.base_url().'user/deactivate_account/'.$user_id.'/'.$uuid.'" class="text-danger">Deactivate</a>';
     * }
     */
}

function get_edit_button($id) {
    return '<a class="btn btn-primary" href="' . base_url() . 'index.php/user/b2as_user?' . $_SERVER ['QUERY_STRING'] . '&eid=' . $id . '"><i class="fa fa-edit"></i>
    Select </a><a role="button" href="'.base_url().'index.php/user/user_privilege_supp?'.$_SERVER['QUERY_STRING'].'&	eid='.$id.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
		Privileges</a>
    ';
    /*
     * <a role="button" href="'.base_url().'general/account?uid='.$id.'" class="btn btn-sm">
     * <span class="glyphicon glyphicon-zoom-in"></span>'.get_app_message('AL0023').'</a>
     */
}

function get_user_actions($user) {
    $id = $user['user_id'];
    return get_edit_button($id);
}

function advanced_search($st = array()) {
    return '<form type="GET" action="' . base_url() . 'index.php/user/b2as_user" autocomplete="off">
    <input type="hidden" name="user_status" value="' . @$st ['status'] . '" >
    <div class="form-group">
      <input type="text" class="form-control" value="' . @$st ['text'] . '" name="st" placeholder="Search...">
    </div>
  </form>';
}
?>
<script>
    $(document).ready(function() {
        // $('#table').DataTable({
        //     "searching": false,
        //     "pageLength": 100
        // });

     <?php 
     $statename = '';

      if($form_data['state'] > 0){
            $statename = get_state_name($form_data['state']);
            }
            else{

                $statename = $form_data['state_txt'];
            }

     ?>   

    // var company_reg_id="<?= $form_data['pan_no'];?>";
    // var travel_licence_no="<?= $form_data['gst_no'];?>";
    // var postal_code="<?= $form_data['pin_code'];?>";
    // var comp_website_link="<?= $form_data['comp_website_link'];?>"; 
    // var comp_email="<?= $form_data['comp_email'];?>"; 
    // var state_txt="<?= $statename;?>"; 
    // //alert(state_txt);
    //  $("#company_reg_id").val(""+company_reg_id);
    //  $("#travel_licence_no").val(""+travel_licence_no);
    //  $("#postal_code").val(""+postal_code);
    //  $("#comp_website_link").val(""+comp_website_link);
    //  $("#comp_email").val(""+comp_email);
    //  $("#state_name").val(""+state_txt);





        $('.toggle-user-status').on('change', function(e) {
            //alert(document.getElementById('base_url'));
            e.preventDefault();
            var _user_status = this.value;
            var _opp_url = app_base_url+'index.php/user/';
            if (parseInt(_user_status) == 1) {
                _opp_url = _opp_url+'activate_account/';
            } else {
                _opp_url = _opp_url+'deactivate_account/';
            }
            _opp_url = _opp_url+$(this).data('user-id')+'/'+$(this).data('uuid');
            toastr.info('Please Wait!!!');
            $.get(_opp_url, function() {
                toastr.info('Updated Successfully!!!');
                window.location.href = document.getElementById('base_url').value;
            });
        });
        $('.toggle-irctc-user-status').on('change', function(e) {
            e.preventDefault();
            $src = $(this);
            var _user_status = this.value;
            var _opp_url = app_base_url+'index.php/user/';
            if (parseInt(_user_status) == 1) {
                _opp_url = _opp_url+'irctc_account_details/';
                _opp_url = _opp_url+$src.data('user-id');
                $('#irctc_details').modal('show').on('shown.bs.modal', function (e) {
                    $('.loader').show();
                    $model = $(this);
                    $.get(_opp_url, function(result) {
                        $('.loader').hide();    
                        $('#irctc_data').html(result);
                    });
                }).on('hidden.bs.modal', function (e) {
                    $src.val($src.data('status'));
                    $('#irctc_data').html('');
                });     
            } else {
                _opp_url = _opp_url+'deactivate_irctc_account/';
                _opp_url = _opp_url+$src.data('user-id');
                toastr.info('Please Wait!!!');
                $.get(_opp_url, function(result) {
                    if($.trim(result)==='1'){
                        $src.data('status', 0);
                        toastr.info('Updated Successfully!!!');
                    }
                    else {
                        $src.data('status', 1);
                        toastr.info('Try Again!');
                    }
        
                }).fail(function() {toastr.info('Try Again!');$src.data('status', 1);});
            }   
        });
        $(document).on('click','#irctc-submit', function(e){
            e.preventDefault();
            this.disabled = true;
    
            $src = $('.toggle-irctc-user-status[data-user-id="'+$('#irctc_user_id').val()+'"]');
            $.post(app_base_url+'index.php/user/irctc_account_edit/'+$src.data('user-id'), $('#irctc_details_form').serialize(), function(result){
                result = $.trim(result);
                console.log(result);
                if(result === '1'){
                    toastr.info('Updated Successfully!!!');
                    $src.data('status', 1);
                } else if(result == '0'){
                    toastr.info('Updated Successfully!!!');
                    $src.data('status', 0);
                }
                else {
                    toastr.info('Try Again!');
                }
                //alert(result);
                $('#irctc_details').modal('hide');
      
            }).fail(function() {toastr.info('Try Again!');$src.data('status', 0);});
        });


   
    });

    $('#pin_code').bind('keyup paste', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
  });

    //  $("#pin_code").keydown(function(event) {
    //     // Allow only backspace and delete
    //     if ( event.keyCode == 46 || event.keyCode == 8 ) {
    //         // let it happen, don't do anything
    //     }
    //     else {
    //         // Ensure that it is a number and stop the keypress
    //         if (event.keyCode < 48 || event.keyCode > 57 ) {
    //             event.preventDefault(); 
    //         }   
    //     }
    // });
    $('#clear_search_filters').click(function(){
        $('.search_filter', "form#search_filter_form").val('');
        $("form#search_filter_form").submit();
    });
  $("#reset_value").click(function() {
    $(this).closest('form').find("input[type=text],input[type=number], textarea").removeAttr('value');
});
</script>






<?php
Js_Loader::$js [] = array(
    'src' => SYSTEM_RESOURCE_LIBRARY . '/DataTables/datatables.js', 'defer' => 'defer');

Js_Loader::$css [] = array(
    'href' => SYSTEM_RESOURCE_LIBRARY . '/DataTables/datatables.css');
