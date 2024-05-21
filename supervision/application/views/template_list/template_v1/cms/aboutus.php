<!-- HTML BEGIN -->
<div class="bodyContent">
	<div class="panel <?=PANEL_WRAPPER?>"><!-- PANEL WRAP START -->
		<div class="panel-heading"><!-- PANEL HEAD START -->

			<div class="panel-title">
				<i class="fa fa-edit"></i> Add ABOUT US For Modules
			</div>
		</div><!-- PANEL HEAD START -->
		<div class="panel-body"><!-- PANEL BODY START -->
			<fieldset><legend><i class="fa fa-plane"></i> </legend>
			<span style="color:#dd4b39"><?php if(isset($message)){ echo $message; } ?></span>
				<form action="<?=base_url().'cms/about_us'?>" enctype="multipart/form-data" class="form-horizontal" method="POST" autocomplete="off">

					
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label"><span class="">  Text  </span></label>
						<div class="col-sm-5">
						<!-- <input type="text" autocomplete="off" name="flight_text" class="normalinput auto-focus valid_class  form-control b-r-0" id="to" placeholder="Flight Text" value="" required /> -->

						<textarea id="w3review" autocomplete="off" name="flight_text" class="normalinput auto-focus valid_class  form-control b-r-0" rows="4" cols="50">

						</textarea>

							<!-- <select name="to_airport" class="form-control" required="">
								<option value="INVALIDIP">Please Select</option>
								<?=generate_options($flight_list)?>
							</select> -->
						</div>
					</div>
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label"><span class=""> Module </span></label>
						<div class="col-sm-5">
					 <input type="text" autocomplete="off" name="module" class="normalinput auto-focus valid_class  form-control b-r-0" id="to" placeholder="Flight" value="" required /> 
					


							<!-- <select name="to_airport" class="form-control" required="">
								<option value="INVALIDIP">Please Select</option>
								<?=generate_options($flight_list)?>
							</select> -->
						</div>
					</div>
					<div class="form-group">
						<label form="about_order" for="about_order" class="col-sm-3 control-label"> Order<span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<input type="number" class="form-control" required="required" id="about_order" placeholder="Order" name="about_order">
						</div>
					</div>
					<div class="form-group">
						<label form="user" for="title" class="col-sm-3 control-label"> Image<span class="text-danger">*</span></label>
						<div class="col-sm-6">
							<input type="file" class="" accept="image/*" required="required" name="top_destination">
						</div>
					</div>

					<div class="well well-sm">
						<div class="clearfix col-md-offset-1">
							<button class=" btn btn-sm btn-success " type="submit">Add</button>
						</div>
					</div>
				</form>
			</fieldset>
		</div><!-- PANEL BODY END -->
		<div class="panel-body">
			<table class="table table-condensed">
				<tr>
					<th>Sno</th>
					<th>Module</th>
					<th>About Text</th>
					<th>Image</th>
					<th>Action</th>
				</tr>
				<?php
				//debug($data_list);exit;
				if (valid_array($data_list) == true) {
					foreach ($data_list as $k => $v) :
				?>
					<tr>
						<td><?=($k+1)?></td>
						<td><?=$v['module']?></td>
						<td><?=$v['text']?></td>
						<td><img src="<?php echo $GLOBALS ['CI']->template->domain_images ($v['image']) ?>" height="100px" width="100px" class="img-thumbnail"></td>
						<td><?php //echo get_status_label($v['status']).get_status_toggle_button($v['status'], $v['id']) ?>
						<a role="button" href="<?php echo base_url() ?>index.php/cms/delete_about_us/<?php echo $v['id'] ?>"><button class="btn btn-sm">Delete</button></a>
						<a role="button" href="<?php echo base_url() ?>index.php/cms/edit_about_us/<?php echo $v['id'] ?>"><button class="btn btn-sm">Edit</button></a>

						</td>
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
		return '<span class="bg-red-active"><i class="fa fa-circle-o"></i> '.get_enum_list('status', INACTIVE).'</span>
		<a role="button" href="" class="hide">Deactivate</a>';
	}
}

function get_status_toggle_button($status, $origin)
{
	if (intval($status) == ACTIVE) {
		return '<a role="button" href="'.base_url().'index.php/cms/deactivate_addbanner/'.$origin.'" class="text-danger">Deactivate</a>';
	} else {
		return '<a role="button" href="'.base_url().'index.php/cms/activate_flight_top_destination/'.$origin.'" class="text-success">Activate</a>';
	}
}

?>
<script src="/medbliss/extras/system/library/javascript/jquery-ui.min.js"></script>
<script type="text/javascript">
    $.widget("custom.catcomplete", $.ui.autocomplete, {
    _create: function() { this._super(), this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)") },
    _renderMenu: function(t, e) {
        var r = this,
            a = "";
        $.each(e, function(e, o) {
            var n;
           o.category != a && (t.append("<li class='ui-autocomplete-category'>" + o.category + "</li>"), a = o.category), n = r._renderItemData(t, o), o.category && n.attr("aria-label", o.category + " : " + o.label)
        })
    }
});
    var cache = {};
    var from_airport = $('#from').val();
    var to_airport = $('#to').val();
 $(".fromflight, .departflight").catcomplete({
        open: function(event, ui) {
        $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
    },
        source: function(request, response) {
            var term = request.term;
            if (term in cache) {
                response(cache[term]);
                return
            } else {
                $.getJSON(app_base_url + "index.php/flight/get_airport_code_list", request, function(data, status, xhr) {
                    if ($.isEmptyObject(data) == true && $.isEmptyObject(cache[""]) == false) {
                        data = cache[""]
                    } else {
                        cache[term] = data;
                        response(cache[term])
                    }
                })
            }
        },
        minLength: 0,
        autoFocus: false,
        select: function(event, ui) {
            var label = ui.item.label;
            var category = ui.item.category;
            if (this.id == 'to') {
                to_airport = ui.item.value
            } else if (this.id == 'from') {
                from_airport = ui.item.value
            }
            $(this).siblings('.loc_id_holder').val(ui.item.id);
            auto_focus_input(this.id)
            //For Multicity-To autofill the next departure city
            if($(this).hasClass('m_arrcity') == true && ui.item.value !='') {
            	var next_depcity_id = $(this).closest('.multi_city_container').next('.multi_city_container').find('.m_depcity').attr('id');
            	if($('#'+next_depcity_id).val() == '') {
	            	$('#'+next_depcity_id).val(ui.item.value);
	            	$('#'+next_depcity_id).siblings('.loc_id_holder').val(ui.item.id);
            	}
            }
        },
        change: function(ev, ui) {
            if (!ui.item) {
                $(this).val("")
            }
        }
    }).bind('focus', function() {
        $(this).catcomplete("search")
    }).catcomplete("instance")._renderItem = function(ul, item) {
        var auto_suggest_value = highlight_search_text(this.term.trim(), item.value, item.label);
        var top = 'Top Searches';
        return $("<li class='custom-auto-complete'>").append('<a><img class="flag_image" src="' + '">' + auto_suggest_value + '</a>').appendTo(ul)
    };
</script>
