$(document).ready(function() {
	var roomLength;
	$('.HroomP').click(function() {

		var sp = parseFloat($(this).siblings('.datemix').text());

		if (sp >= 0) {
			if (sp < 3) {

				var mod = $('#modify').val();
				$(this).siblings('.datemix').text(sp + 1);
				$(this).siblings('.Hroom').val(sp + 1);
				roomLength = $("div.repeatedroom").length;
				roomLength = roomLength + 1;

				if (mod == 'mod') {
					var room = '<div class="repeatedroom Aroom' + roomLength + '">' +
                        '<br>' +
                        '<div class="childgroup" data-roomid="' + roomLength + '">' +
                          '<div class="row">' +
                            '<div class="col-md-2">' +
                              '<label class="invisible">Rooms</label>' +
                              '<div class="roomnumpn">' +
                                '<span class="numroompn"><br><i class="fa fa-bed"></i> <strong>( ' + roomLength + ' )</strong> </span>' +
                              '</div>' +
                            '</div>' +
                            '<div class="col-md-5 fiveh">' +
                              '<label>Adult</label>' +
                              '<div class="selectedwrapnum">' +
                                '<div class="persnm padult"></div>' +
                                '<div class="onlynumwrap">' +
                                  '<div class="onlynum">' +
                                    '<span class="btnminus meex cmnum aminus">-</span>' +
                                    '<div class="datemix meex">1</div>' +
                                    '<input class="apax" type="hidden" name="adult[]" value="1" required>' +
                                    '<span class="btnplus meex cmnum aplus">+</span>' +
                                  '</div>' +
                                '</div>' +
                              '</div>' +
                            '</div>' +
                            '<div class="col-md-5 fiveh">' +
                              '<label>Children(2-12 yrs)</label>' +
                              '<div class="selectedwrapnum">' +
                                '<div class="persnm pachildrn"></div>' +
                                '<div class="onlynumwrap">' +
                                  '<div class="onlynum">' +
                                    '<span class="btnminus meex cmnum HCminus">-</span>' +
                                    '<div class="datemix meex">0</div>' +
                                    '<input class="HCpax" type="hidden" name="child[]" value="0" required>' +
                                    '<span class="btnplus meex cmnum HCplus">+</span>' +
                                  '</div>' +
                                '</div>' +
                              '</div> ' +
                            '</div>' +
                          '</div>' +
                        '</div>' +
                      '</div>';
        } else {

          var room = '<div class="repeatedroom Aroom' + roomLength + '">' +
                        '<br>' +
                        '<div class="childgroup" data-roomid="' + roomLength + '">' +
                          '<div class="row">' +
                            '<div class="col-md-2">' +
                              '<label class="invisible">Rooms</label>' +
                              '<div class="roomnumpn">' +
                                '<span class="numroompn"><br><i class="fa fa-bed"></i> <strong>( ' + roomLength + ' )</strong> </span>' +
                              '</div>' +
                            '</div>' +
                            '<div class="col-md-5 fiveh">' +
                              '<label>Adult</label>' +
                              '<div class="selectedwrapnum">' +
                                '<div class="persnm padult"></div>' +
                                '<div class="onlynumwrap">' +
                        					'<div class="onlynum">' +
                          					'<span class="btnminus meex cmnum aminus">-</span>' +
                          					'<div class="datemix meex">1</div>' +
                          					'<input class="apax" type="hidden" name="adult[]" value="1" required>' +
                          					'<span class="btnplus meex cmnum aplus">+</span>' +
                        					'</div>' +
                      					'</div>' +
                    					'</div>  ' +
                  					'</div>' +
                  					'<div class="col-md-5 fiveh">' +
                    					'<label>Children(2-12 yrs)</label>' +
                    					'<div class="selectedwrapnum">' +
                      					'<div class="persnm pachildrn"></div>' +
                      					'<div class="onlynumwrap">' +
                        					'<div class="onlynum">' +
                          					'<span class="btnminus meex cmnum HCminus">-</span>' +
                          					'<div class="datemix meex">0</div>' +
                          					'<input class="HCpax" type="hidden" name="child[]" value="0" required>' +
                          					'<span class="btnplus meex cmnum HCplus">+</span>' +
                        					'</div>' +
                      					'</div>' +
                    					'</div> ' +
                  					'</div>' +
                					'</div>' +
              					'</div>' +
            					'</div>';
				}
				if (roomLength <= 3) {
                    $('.addedRooms:last').append(room); // end append
                  } else {
                  	return false;
                  }

                }
              }
            });
$('.HroomM').click(function() {
	var sp = parseFloat($(this).siblings('.datemix').text());
	if (sp > 0) {
		if (sp - 1 > 0 && sp - 1 <= 3) {
			$(".Aroom" + sp).remove();
			$(this).siblings('.datemix').text(sp - 1);
			$(this).siblings('.Hroom').val(sp - 1);
		}
	}
});



$(document).on('click', '.minus.child', function() {
	var sp = parseFloat($(this).siblings('.datemix').text());
	if (sp > 0) {
		$(this).siblings('.datemix').text(sp - 1);
		$(this).siblings('.pax').val(sp - 1);
	} else {
            $(this).siblings('.datemix').text(0); // Otherwise put a 0 there
            $(this).siblings('.pax').val(0); // Otherwise put a 0 there
          }
        });



$(document).on('click', '.minus.adult', function() {
	var sp = parseFloat($(this).siblings('.datemix').text());
        if (sp > 0) { //alert($(this).parents('div.row').children('div:last-child').find('.pax').prop('class'));
        var inf = parseFloat($(this).parents('div.row').children('div:last-child').find('.pax').val());
        var adt = $(this).siblings('.pax').val();

            //alert(inf);
            if (adt <= inf) { //alert(inf);
                //$('#infant').val(adt - 1);
                //$('#infant').siblings('.datemix').text(adt - 1);
                $(this).parents('div.row').children('div:last-child').find('.datemix').text(adt - 1);
                $(this).parents('div.row').children('div:last-child').find('.pax').val(adt - 1);


              }
              $(this).siblings('.datemix').text(sp - 1);
              $(this).siblings('.pax').val(sp - 1);


            } else {
            $(this).siblings('.datemix').text(0); // Otherwise put a 0 there
            $(this).siblings('.pax').val(0); // Otherwise put a 0 there
          }
        });



$(document).on('click', '.minus.infant', function() {
	var sp = parseFloat($(this).siblings('.datemix').text());
	if (sp > 0) {
		$(this).siblings('.datemix').text(sp - 1);
		$(this).siblings('.pax').val(sp - 1);
	} else {
            $(this).siblings('.datemix').text(0); // Otherwise put a 0 there
            $(this).siblings('.pax').val(0); // Otherwise put a 0 there
          }
        });


    //-----------------------------------------------------------------



    $(document).on('click', '.HCminus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	var sp = parseFloat($(this).siblings('.datemix').text());

    	if (sp > 0) {
            //console.log(sp);
            //if (sp-1 > 0 && sp-1 <= 2) {
            	$(this).closest('.childgroup').find(".childAge" + sp).remove();
            	$(this).siblings('.datemix').text(sp - 1);
            	$(this).siblings('.HCpax').val(sp - 1);
            //}
          }
        });

    $(document).on('click', '.HCminus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	if (sp > 0) {
    		$(this).closest('.childgroup').find(".childAge" + sp).remove();
    		$(this).siblings('.datemix').text(sp - 1);
    		$(this).siblings('.HCpax').val(sp - 1);
    	}
    });

    $(document).on('click', '.cplus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	if (sp <= 9) {
    		$(this).siblings('.datemix').text(sp + 1);
    		$(this).siblings('.cpax').val(sp + 1);
    	} else {
    		return false;
    	}
    });
    $(document).on('click', '.cminus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	if (sp > 1) {
    		$(this).siblings('.datemix').text(sp - 1);
    		$(this).siblings('.cpax').val(sp - 1);
    	} else {
    		return false;
    	}
    });

    $(document).on('click', '.aplus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	if (sp <= 3) {
    		$(this).siblings('.datemix').text(sp + 1);
    		$(this).siblings('.apax').val(sp + 1);
    	} else {
    		return false;
    	}
    });
    $(document).on('click', '.aminus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	if (sp > 1) {
    		$(this).siblings('.datemix').text(sp - 1);
    		$(this).siblings('.apax').val(sp - 1);
    	} else {
    		return false;
    	}
    });


    $(document).on('click', '.taplus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	if (sp <= 14) {
    		$(this).siblings('.datemix').text(sp + 1);
    		$(this).siblings('.tapax').val(sp + 1);
    	} else {
    		return false;
    	}
    });
    $(document).on('click', '.taminus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	if (sp > 1) {
    		$(this).siblings('.datemix').text(sp - 1);
    		$(this).siblings('.tapax').val(sp - 1);
    	} else {
    		return false;
    	}
    });
    $(document).on('click', '.tcaplus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	if (sp <= 17) {
    		$(this).siblings('.datemix').text(sp + 1);
    		$(this).siblings('.tcapax').val(sp + 1);
    	} else {
    		return false;
    	}
    });
    $(document).on('click', '.tcaminus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	if (sp > 1) {
    		$(this).siblings('.datemix').text(sp - 1);
    		$(this).siblings('.tcapax').val(sp - 1);
    	} else {
    		return false;
    	}
    });
    $(document).on('click', '.tcminus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
        // var sp = parseFloat($(this).siblings('.datemix').text());
        if (sp > 0) {
        	$('#transfers .childgroup').find(".childAge" + sp).remove();
        	$(this).siblings('.datemix').text(sp - 1);
        	$(this).siblings('.tcpax').val(sp - 1);
        }
      });

    $(document).on('click', '.HCplus', function() {
    	var sp = parseFloat($(this).siblings('.datemix').text());
    	if (sp >= 0) {
    		var mod = $('#modify').val();

    		var caLength = $(this).closest('.childgroup').find('.childAge').length;
    		caLength = caLength + 1;
    		var roomid = $(this).closest('.childgroup').data('roomid');
    		if (mod == 'mod') {
    			var childAge = '<div class="row">' +
                      			'<div class="col-md-2 col-xs-4 mefullwdhtl">' +
                      			'</div>' +
                      			'<div class="col-md-5 fiveh childAge childAge' + caLength + '">' +
                        			'<span class="formlabel">Child - ' + caLength + ' age</span>' +
                        			'<div class="selectedwrapnum">' +
                          			'<div class="persnm pachildrn"></div>' +
                          			'<div class="onlynumwrap">' +
                            			'<div class="onlynum">' +
                              			'<span class="btnminus meex cmnum cminus">-</span>' +
                              			'<div class="datemix meex">1</div>' +
                              			'<input class="cpax" type="hidden" name="childAge_' + roomid + '[]" value="1" required>' +
                              			'<span class="btnplus meex cmnum cplus">+</span>' +
                            			'</div>' +
                          			'</div>' +
                        			'</div>' +
                      			'</div>' +
                    			'</div>';
    		} else {
    			var childAge = '<div class="row">' +
                      			'<div class="col-md-2 col-xs-4 mefullwdhtl">' +
                      			'</div>' +
                            '<div class="col-md-5 fiveh childAge cl_rt pull-right childAge' + caLength + '">' +
                        			'<span class="formlabel">Child - ' + caLength + ' age</span>' +
                        			'<div class="selectedwrapnum">' +
                          			'<div class="persnm pachildrn"></div>' +
                          			'<div class="onlynumwrap">' +
                            			'<div class="onlynum">' +
                              			'<span class="btnminus meex cmnum cminus">-</span>' +
                              			'<div class="datemix meex">1</div>' +
                              			'<input class="cpax" type="hidden" name="childAge_' + roomid + '[]" value="1" required>' +
                              			'<span class="btnplus meex cmnum cplus">+</span>' +
                            			'</div>' +
                          			'</div>' +
                        			'</div>' +
                      			'</div>' +
                    			'</div>';
    		}
    		if (caLength <= 2) {
    			$(this).siblings('.datemix').text(sp + 1);
    			$(this).siblings('.HCpax').val(sp + 1);
    			$(this).closest('.childgroup').append(childAge);
    		} else {
    			return false;
    		}

    	} else {
            $(this).siblings('.datemix').text(0); // Otherwise put a 0 there
            $(this).siblings('.HCpax').val(0); // Otherwise put a 0 there
          }
        });

});