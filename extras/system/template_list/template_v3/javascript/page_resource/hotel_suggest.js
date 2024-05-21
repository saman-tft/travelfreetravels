$(document).ready(function() {

    var max_rooms = 3;
    var min_rooms = 1;
    var max_childs = 2;
    $('.add_rooms').on('click', function(e) {
        e.preventDefault();
        var _visible_rooms = parseInt($('#room-count').val());
        _visible_rooms = _visible_rooms + 1;
        if(_visible_rooms==3)
        {
          //  _visible_rooms = _visible_rooms - 1;
        }
        toggle_add_remove_rooms(_visible_rooms);

        if (_visible_rooms <= max_rooms) {
            $('#room-count').val(_visible_rooms);
            for (var i = 1; i <= (_visible_rooms); i++) {
                $('#room-wrapper-' + i).show()
            }
            console.log(_visible_rooms);
            validate_rooms(_visible_rooms)
        }
        total_pax_summary()
    });
    $('.remove_rooms').on('click', function(e) {
        e.preventDefault();
        var _visible_rooms = parseInt($('#room-count').val());
        toggle_add_remove_rooms((_visible_rooms - 1));
        
        if (_visible_rooms > min_rooms) {
            $('#room-wrapper-' + _visible_rooms).hide();
            _visible_rooms = _visible_rooms - 1;
            $('#room-count').val(_visible_rooms);
            validate_rooms(_visible_rooms)
        }
        total_pax_summary()
    });
  $('.add_roomscrs').on('click', function(e) {
        e.preventDefault();
        var _visible_rooms = parseInt($('#room-countcrs').val());
        _visible_rooms = _visible_rooms + 1;
         if(_visible_rooms==3)
        {
            _visible_rooms = _visible_rooms - 1;
        }
        toggle_add_remove_roomscrs(_visible_rooms);
        
        if (_visible_rooms <= max_rooms) {
            $('#room-countcrs').val(_visible_rooms);
            for (var i = 1; i <= (_visible_rooms); i++) {
                $('#room-wrappercrs-' + i).show()
            }
            validate_roomscrs(_visible_rooms)
        }
        total_pax_summarycrs()
    });
    $('.remove_roomscrs').on('click', function(e) {
        e.preventDefault();
        var _visible_rooms = parseInt($('#room-countcrs').val());
        toggle_add_remove_roomscrs((_visible_rooms - 1));
        if (_visible_rooms > min_rooms) {
            $('#room-wrappercrs-' + _visible_rooms).hide();
            _visible_rooms = _visible_rooms - 1;
            $('#room-countcrs').val(_visible_rooms);
            validate_roomscrs(_visible_rooms)
        }
        total_pax_summarycrs()
    });
     function toggle_add_remove_roomscrs(current_rooms) {
        if (current_rooms >= max_rooms) {
            $('.add_roomscrs').hide()
        } else {
            $('.add_roomscrs').show()
        }
        if (current_rooms <= min_rooms) {
            $('.remove_roomscrs').hide()
        } else {
            $('.remove_roomscrs').show()
        }
    }
    function toggle_add_remove_rooms(current_rooms) {
        if (current_rooms >= max_rooms) {
            $('.add_rooms').hide()
        } else {
            $('.add_rooms').show()
        }
        if (current_rooms <= min_rooms) {
            $('.remove_rooms').hide()
        } else {
            $('.remove_rooms').show()
        }
    }
function validate_roomscrs(room) {
        for (var i = (parseInt(room) + 1); i <= max_rooms; i++) {
            $('input, select', $('#room-wrappercrs-' + i)).attr('disabled', 'disabled')
        }
        for (var i = (parseInt(room)); i >= min_rooms; i--) {
            $('input, select', $('#room-wrappercrs-' + i)).removeAttr('disabled')
        }
    }
    function validate_rooms(room) {
        for (var i = (parseInt(room) + 1); i <= max_rooms; i++) {
            $('input, select', $('#room-wrapper-' + i)).attr('disabled', 'disabled')
        }
        for (var i = (parseInt(room)); i >= min_rooms; i--) {
            $('input, select', $('#room-wrapper-' + i)).removeAttr('disabled')
        }
    }
    var pri_visible_room = $('#pri_visible_room').val();
    toggle_add_remove_rooms(pri_visible_room);
    validate_rooms(pri_visible_room);
    total_pax_summary();
    $('#hotel_search .input-number').on('change blur', function() {
        total_pax_summary()
    });
    $('#hotel_search input[name="child[]"]').on('change', function() {
        var current_rooms = $(this).closest('.oneroom');
        var child_count = parseInt(this.value);
        if (child_count < 1) {
            $('.chilagediv', current_rooms).hide()
        } else {
            $('.chilagediv', current_rooms).show();
            for (var j = 1; j <= child_count; j++) {
                $('.child-age-wrapper-' + j, current_rooms).show()
            }
            for (var j = (child_count + 1); j <= max_childs; j++) {
                $('.child-age-wrapper-' + j, current_rooms).hide()
            }
        }
    });
$('#hotel_search2c input[name="child[]"]').on('change', function() {
        var current_rooms = $(this).closest('.oneroom');
        var child_count = parseInt(this.value);
        if (child_count < 1) {
            $('.chilagediv', current_rooms).hide()
        } else {
            $('.chilagediv', current_rooms).show();
            for (var j = 1; j <= child_count; j++) {
                $('.child-age-wrapper-' + j, current_rooms).show()
            }
            for (var j = (child_count + 1); j <= max_childs; j++) {
                $('.child-age-wrapper-' + j, current_rooms).hide()
            }
        }
    });
function total_pax_summarycrs() {
        var total_rooms = $('#room-countcrs').val();
        var total_adults = 0;
        for (var i = 0; i < parseInt(total_rooms); i++) {
            var room_adult_count = $("#adult_text_" + i).val();
            if (parseInt(room_adult_count) == 0) {
                $("#adult_text_" + i).val(1);
            }
        }
        $('#hotel_search2c [name="adult[]"]').not(':disabled').each(function() {
            total_adults = total_adults + parseInt(this.value)
        });
        var total_child = 0;
        $('#hotel_search2c [name="child[]"]').not(':disabled').each(function() {
            total_child = total_child + parseInt(this.value)
        });
        var room_summary = '';
        room_summary += total_adults;
        if (total_adults > 1) {
            room_summary += ' Adults,'
        } else {
            room_summary += ' Adult,'
        }
        if (total_child > 0) {
            room_summary += total_child;
            if (total_child > 1) {
                room_summary += ' Children,'
            } else {
                room_summary += ' Child,'
            }
        }
        room_summary += total_rooms;
        if (total_rooms > 1) {
            room_summary += ' Rooms'
        } else {
            room_summary += ' Room'
        }
        // alert();
        $('#hotel-pax-summary2g').text(room_summary);
        
        $("#data_traveler_all_hotel").attr('data-tip',room_summary);
    }
    function total_pax_summary() {
        var total_rooms = $('#room-count').val();
        var total_adults = 0;
        for (var i = 0; i < parseInt(total_rooms); i++) {
            var room_adult_count = $("#adult_text_" + i).val();
            if (parseInt(room_adult_count) == 0) {
                $("#adult_text_" + i).val(1);
            }
        }
        $('#hotel_search [name="adult[]"]').not(':disabled').each(function() {
            total_adults = total_adults + parseInt(this.value)
        });
        var total_child = 0;
        $('#hotel_search [name="child[]"]').not(':disabled').each(function() {
            total_child = total_child + parseInt(this.value)
        });
        var room_summary = '';
        room_summary += total_adults;
        if (total_adults > 1) {
            room_summary += ' Adults,'
        } else {
            room_summary += ' Adult,'
        }
        if (total_child > 0) {
            room_summary += total_child;
            if (total_child > 1) {
                room_summary += ' Children,'
            } else {
                room_summary += ' Child,'
            }
        }
        room_summary += total_rooms;
        if (total_rooms > 1) {
            room_summary += ' Rooms'
        } else {
            room_summary += ' Room'
        }
        // alert();
        $('#hotel-pax-summary3g').text(room_summary);
        
        $("#data_traveler_all_hotel").attr('data-tip',room_summary);
    }
});
$(document).ready(function() {
    function set_hotel_cookie_data() {
        var s_params = $('#hotel_search').serialize().trim();
        setCookie('hotel_search', s_params, 100)
    }
    var cache = {};
    $(".hotel_city").catcomplete({
        open: function(event, ui) {
            $('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
        },
        source: function(request, response) {
            var term = request.term;
            if (term in cache) {
                response(cache[term]);
                return
            }
            console.log("hotel_city");
            $.getJSON(app_base_url + "index.php/ajax/get_hotel_city_list", request, function(data, status, xhr) {
                cache[term] = data;
                response(data)
            })
        },
        minLength: 0,
        autoFocus: false,
        select: function(event, ui) {
            var label = ui.item.label;
            var category = ui.item.category;
            $(this).siblings('.loc_id_holder').val(ui.item.id);
            $('#hotel_checkin').focus()
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
        var hotel_count = '';
        var count = parseInt(item.count);
        if (count > 0) {
            var h_lab = '';
            if (count > 1) {
                h_lab = 'Hotels'
            } else {
                h_lab = 'Hotel'
            }
            hotel_count = '<span class="hotel_cnt">(' + parseInt(item.count) + ' ' + h_lab + ')</span>'
        }
        return $("<li class='custom-auto-complete'>").append('<a> <span class="fal fa-map-marker-alt"></span> ' + auto_suggest_value + ' ' + hotel_count + '</a>').appendTo(ul)
    };
    $('#hotel_checkin, #hotel_checkout').on('change', function(e) {
        e.preventDefault();
        var from_date = $('#hotel_checkin').val();
        var to_date = $('#hotel_checkout').val();
        if (from_date != '' && to_date != '') {
            var diffDays = parseInt(get_day_difference($('#hotel_checkin').datepicker('getDate'), $('#hotel_checkout').datepicker('getDate')));
            if (parseInt(diffDays) > 10 || diffDays < -10) {
                diffDays = 10;
                $('#hotel_checkout').val(add_days_to_date(from_date, diffDays))
            } else if (diffDays < 0) {
                diffDays = diffDays * -1;
                $('#hotel_checkout').val(add_days_to_date(from_date, diffDays))
            } else if (diffDays == 0) {
                diffDays = 1;
                $('#hotel_checkout').val(add_days_to_date(from_date, diffDays))
            }
            $('#no_of_nights').val(diffDays)
        }
    });

    function add_days_to_date(from_date, number_of_days) {
        from_date = from_date.split('-');
        var to_date = new Date(from_date[2], parseInt(from_date[1]) - 1, (parseInt(from_date[0]) + number_of_days));
        month = '' + (to_date.getMonth() + 1), day = '' + to_date.getDate(), year = to_date.getFullYear();
        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [day, month, year].join('-')
    }
    $('#no_of_nights').on('change', function() {
        var from_date = $('#hotel_checkin').val();
        var number_of_nights = parseInt(this.value);
        if (from_date != '') {
            var to_date = add_days_to_date(from_date, number_of_nights);
            $('#hotel_checkout').val(to_date)
        }
    })

    $('#hotel-form-submit').on('click', function (e) {
        
        
            var _from_loc1 = $('#hotel_destination_search_name').val();
            var hotel_checkin = $('#hotel_checkin').val();
            var hotel_checkout = $('#hotel_checkout').val();
            
            if ((_from_loc1 =="")) {
                show_alert_content('Enter Location.', '#hotel-alert-box');
                e.preventDefault();
                // return ''
            }
            else
            {
                show_alert_content('', '#hotel-alert-box');
            }

            if ((hotel_checkin =="")) {
                show_alert_content('Select Check in Date.', '#hotel-alert-box_checkin');
                e.preventDefault();
                // return ''
            }
            else
            {
                show_alert_content('', '#hotel-alert-box_checkin');
            }

            if ((hotel_checkout =="")) 
            {
                show_alert_content('Select checkout Date.', '#hotel-alert-box_checkout');
                e.preventDefault();
                // return ''
            }
            else
            {
                show_alert_content('', '#hotel-alert-box_checkout');

            }
            
        /*var _adult = parseInt($('#OWT_adult').val());
        var _child = parseInt($('#OWT_child').val());
        var _infant = parseInt($('#OWT_infant').val());
        var _content = '';
        if (_infant > 0 && _adult < _infant) {
            alert();
            _content = '1 Infant Per Adult Allowed'
            show_alert_content(_content);
            e.preventDefault();
        }
        show_alert_content(_content)*/
    });
    $('#hotel_checkout').on('change', function () {
        show_alert_content('', '#hotel-alert-box_checkout');
    });
    $('#hotel_checkin').on('change', function () {
        show_alert_content('', '#hotel-alert-box_checkin');
    });
    $('#hotel_destination_search_name').on('change', function () {
        show_alert_content('', '#hotel-alert-box');
    });



});

function show_alert_content(content, container) {
    // alert("ff");
    if (typeof container == "undefined") {
        container = '.alert-content'
    }
    $(container).html(content);
    if (content.length > 0) {
        $('.alert-wrapper').removeClass('hide')
    } else {
        $('.alert-wrapper').addClass('hide')
    }
}