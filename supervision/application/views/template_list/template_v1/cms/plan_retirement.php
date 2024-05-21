<!-- HTML BEGIN -->
<div class="bodyContent">
  <div class="row">
    <div class="pull-left" style="margin:5px 0">
      <a href="<?=base_url().'index.php/cms/add_invester'?>">
        <button  class="btn btn-primary btn-sm pull-right amarg">Add Investers</button>
      </a>
    </div>
  </div>
  <div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
    <div class="panel-heading"><!-- PANEL HEAD START -->
      <div class="panel-title">
        <i class="fa fa-edit"></i> Headings
      </div>
    </div><!-- PANEL HEAD START -->
    
    <div class="panel-body table-responsive">
      <table class="table table-condensed">
        <tr>
          <th>Sl no</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Passport Number</th>
          <!-- <th>Investment</th> -->
          <th>Passport ID</th>
          <th>Passport Copy</th>
          <th>Passport Select</th>
          <th>Package</th>
          <th>Payment Status</th>
          <th>View PDF</th>
          <th>Action</th>
          <th>Message</th>
        </tr>
        <?php
        // debug($plan_retirement);exit;
        if (valid_array($plan_retirement) == true) {
          foreach ($plan_retirement as $k => $v) :
          $action = '<form method="post" action="'.base_url().'index.php/user/show_investor_pdf">
       <input type="submit" class="btn btn-default btn-sm btn-primary" value="View PDF" />
       <input type="hidden" value="'.$v['id'].'"  name="id" />
     </form><!--<a role="button" href="'.base_url().'index.php/cms/contact_us/'.$v['id'].'"><button class="btn btn-sm">Edit</button></a>-->'; 
     $edit_action = '<form method="post" action="'.base_url().'index.php/user/edit_investor?bid='.$v['id'].'">
       <input type="submit" class="btn btn-default btn-sm btn-primary" value="Edit" />
       <input type="hidden" value="'.$v['id'].'"  name="id" />
     </form><!--<a role="button" href="'.base_url().'index.php/user/edit_investor/'.$v['id'].'"><button class="btn btn-sm">Edit</button></a>-->'; 
     $status_button = '<a role="button" href="'.base_url().'index.php/user/delete_investor/'.$v['id'].'"><button class="label label-danger">Delete</button></a>'; 
     $email_button = '<a class="btn send_email_voucher" data-app-status="' . $v['payment_status'] . '"   data-app-reference="' . $v['app_reference'] . '" data-invester-id="' . $v['id'] . '" data-recipient_email="' . $v['email'] . '"><i class="far fa-envelope"></i> Email Voucher</a>'; 
     $confirm_button = '<a role="button" href="'.base_url().'index.php/user/confirm_investor/'.$v['id'].'"><button class="label label-success">Confirm</button></a>';  
        ?>
          <tr>
            <td><?=($k+1)?></td>
            <td><?=$v['fullname']?></td>
            <td><?=$v['phone']?></td>
            <td><?=$v['email']?></td>
            <td><?=$v['passno']?></td>
            <!-- <td><?=$v['investment']?></td> -->
            <td><img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template).$v['passid']; ?>" height="100px" width="100px" class="img-thumbnail"></td>
            <td><img src="<?php echo $GLOBALS['CI']->template->domain_images($GLOBALS['CI']->template).$v['passcopy']; ?>" height="100px" width="100px" class="img-thumbnail"></td>
            <td><?=$v['packselect']?></td>
            <td><?=str_replace('_', ' ', $v['package'])?></td>
            <td><?=$v['payment_status']?></td>
            <td><?=$action?></td>
            <td><?=$edit_action.' '.$status_button.' '.$confirm_button.' '.$email_button;?></td>
            <td><?=$v['message']?></td>
            </tr>
          </tr>
        <?php
          endforeach;
        } else {
          echo '<tr><td>No Data Found</td></tr>';
        }
        ?>
      </table>
    </div>
  </div><!-- PANEL WRAP END -->
</div>
<?php 
function get_status_label($status)
{
  if (intval($status) == ACTIVE) {
    return '<span class="label label-success"><i class="fa fa-circle-o"></i> '.get_enum_list('status', ACTIVE).'</span>
  <a role="button" href="" class="hide">'.get_app_message('AL0021').'</a>';
  } else {
    return '';
  }
}

function get_status_toggle_button($status, $origin)
{
  $status_options = get_enum_list('status');
  return '<select class="toggle-user-status" data-origin="'.$origin.'">'.generate_options($status_options, array($status)).'</select>';
}
function get_edit_button($origin)
{
  return '<a role="button" href="'.base_url().'index.php/cms/add_investment_chart?'.$_SERVER['QUERY_STRING'].'& origin='.$origin.'" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
    '.get_app_message('AL0022').'</a>
    ';
  
}

?>

<script type="text/javascript">
  $('.send_email_voucher').on('click', function (e) {
            // $("#mail_voucher_modal").modal('show');
            // $('#mail_voucher_error_message').empty();
            email12 = $(this).data('recipient_email');
            //$("#voucher_recipient_email").val(email);
            app_reference = $(this).data('app-reference');
            invester_id = $(this).data('invester-id');
            app_status = $(this).data('app-status');
            // $("#send_mail_btn").off('click').on('click', function (e) {
                // email = $("#voucher_recipient_email").val();
                email = email12;
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if (email != '') {
                    if (!emailReg.test(email)) {
                        $('#mail_voucher_error_message').empty().text('Please Enter Correct Email Id');
                        return false;
                    }

                    var _opp_url = app_base_url + 'index.php/user/invester_email_voucher/';
                    _opp_url = _opp_url + app_reference + '/' + app_status + '/email_voucher/' + email + '/' + invester_id;
                    toastr.info('Please Wait!!!');
                    $.get(_opp_url, function () {

                        toastr.info('Email sent  Successfully!!!');
                        $("#mail_voucher_modal").modal('hide');
                    });
                } else {
                    $('#mail_voucher_error_message').empty().text('Please Enter Email ID');
                }
            // });
        });
</script>