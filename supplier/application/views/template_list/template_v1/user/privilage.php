<div id="general_user" class="bodyContent">
	<div class="panel panel-default">
		<!-- PANEL WRAP START -->
		<!-- PANEL HEAD START -->
		<div class="panel-body">
<form id="user_previlege_form" action="<?= base_url().'index.php/user/privilege_management?filter=user_type&q=2&user_status='.$user_status?>"
					method="POST">
					<input type="hidden" value="<?=@$uid; ?>" name="user_id" />
<?php
/**
 * ********************** GENERATE CURRENT PAGE TABLE ***********************
 */
echo get_table ( @$privilage_list );
/**
 * ********************** GENERATE CURRENT PAGE TABLE ***********************
 */
?>
<div class="well well-sm text-center">
						<button class="btn btn-primary" type="submit">Save</button>
						<button class="btn btn-warning btn-frm-reset" type="reset">Reset</button>
					</div>
				</form>
			</div>
			<!-- PANEL BODY END -->
		</div>
		<!-- PANEL WRAP END -->
	</div>

<!-- HTML END -->
<?php
function get_table($privilage_list = '') {
	$table = '';
	$table .= '
   <div class="clearfix">
   <table class="table table-condensed table-bordered">';
	$table .= '<thead><tr>
   <th><i class="fa fa-sort-numeric-asc"></i> SLNo</th>
  
   <th>Previlage Description</th>
   <th>Select <button class="btn btn-xs btn-primary" id="btn_select_all" data-check="" type="button">All</button></th>
   </tr></thead><tbody>';

	// $header_pivilege = array("p1","p2","p3","p62","p4","p86","p88","p78","p9","p10","p82","p11","p23","p68","p19","p16");
	if (valid_array ( $privilage_list ) == true) {
		$segment_3 = $GLOBALS ['CI']->uri->segment ( 3 );
		$current_record = (empty ( $segment_3 ) ? 0 : $segment_3);
		foreach ( $privilage_list as $k => $v ) {
			if($v ['privillege_name'] != 'Testimonials'){
			// Booking
			if($v['parent']==1){
				$table .= '<tr class="parent_sec">';
			}
			else{
				$table .= '<tr class="child_sec">';
			}

			$table .= '<td>' . (++ $current_record) . '</td>' ; 
			if($v['parent']==1){
			$table .= '<td class="parent_'.$v['id'].'"><b>'.str_replace("_", " ", $v ['privillege_name']).'</b>' ;
			}
			// elseif ($v['parent']==2) {
			// $table .='<td class="parent_'.$v['parent_id'].'_child_'.$v['id'].'" style="padding-left: 20;"><i>'.str_replace("_", " ", $v ['privillege_name']).'</i>';
			// }
			else {
				$table .= '<td class=" parent_'.$v['parent_id'].'_child_'.$v['id'].'" style="padding-left: 40;">'.str_replace("_", " ", $v ['privillege_name']) ;
			}
			$table .= '</td>
			<td class="parent_check'.$v['parent_id'].'"><input type="checkbox" name="user_previlages[]" value="'.$v['id'].'" class="toggle-user-previlages ';
			if($v['parent']==1){
				$table .= 'parentCheckBox';
			}
			else{
				$table .= 'childCheckBox';
			} 
			$table.='"'.$v['checked'].'/></td>
			</tr>';
		}

	}//End of testimonial check if
	} else {
		$table .= '<tr><td colspan="8">' . get_app_message ( 'AL005' ) . '</td></tr>';
	}
	$table .= '</tbody></table></div>';
	return $table;
}

?>
<script>
$(function(){
	$('#btn_select_all').on('click', function(){
			var check = $(this).attr('data-check');
			// alert(check);
			if(check == ''){
				$('[name="user_previlages[]"]').prop('checked',true);
				$(this).attr('data-check', 'checked').text('Reset');
			} else if(check == 'checked') {
				$('#user_previlege_form').get(0).reset();
				$(this).attr('data-check', '').text('All');
			}
	});

	$(".btn-frm-reset").on("click",function(){

		$('input:checkbox').removeAttr('checked');

	});
});
$(document).ready(function() {
        //clicking the parent checkbox should check or uncheck all child checkboxes
        $(".parentCheckBox").click(
            function() {
            	console.log($(this).val());
                //$(this).parents('fieldset:eq(0)').find('.childCheckBox').attr('checked', this.checked);
            }
        );
        //clicking the last unchecked or checked checkbox should check or uncheck the parent checkbox
        $('.childCheckBox').click(
            function() {
            	console.log($(this).val());
                // if ($(this).parents('fieldset:eq(0)').find('.parentCheckBox').attr('checked') == true && this.checked == false)
                //     $(this).parents('fieldset:eq(0)').find('.parentCheckBox').attr('checked', false);
                // if (this.checked == true) {
                //     var flag = true;
                //     $(this).parents('fieldset:eq(0)').find('.childCheckBox').each(
	               //      function() {
	               //          if (this.checked == false)
	               //              flag = false;
	               //      }
                //     );
                //     $(this).parents('fieldset:eq(0)').find('.parentCheckBox').attr('checked', flag);
             //   }
            }
        );
    }
);
</script>
