$(document).ready(function () {
//added this new function
$("input").focus(function () {
    $(this).removeClass("invalid-ip").parent().find(".formerror").remove();
  });
  $("select").focus(function () {
    $(this).removeClass("invalid-ip").parent().find(".formerror").remove();
  });
    var user_details_confirmed = 0;

    // make payment 
    $('#make-payment-btn').on('click', function () {



        //Hiding Continue Button
        $('.alert-wrapper').addClass('hide');
        $('.alert-content').text('');
        var balance_check = $('.balance_check').text();
        user_details_confirmed = 1;
        if (balance_check == 1) {
            $("#balance-low-modal").modal("show");
            $('#passenger-confirm-modal').modal('hide');
            return false;
        }
        else {
            $('.continue_booking_button').attr('disabled', true);
            $('<button class="btn btn-success" disabled="disabled"> Processing....</button>').insertAfter($('.continue_booking_button:eq(0)'));
            $('.continue_booking_button').hide();
           
            $('#passenger-confirm-modal').modal('hide');
            showModal();
        }

        user_details_confirmed = 0;
    });



    $('#billing-country').on('change', function () {
        if (this.value in city_list_cache) {
            update_city_options(this.value);
        } else {
            fill_city_list(this.value);
        }
    });
    $("#before_country_code").on('change', function () {
        var selected_country_value = this.value;
        $("#after_country_code").val(selected_country_value);
    });
    /**
    *Cache city list based on country
    */
    var city_list_cache = {};
    function fill_city_list(country_name) {
        $.get(app_base_url + "index.php/ajax/get_city_list/" + country_name, function (city_list) {
            city_list_cache[country_name] = city_list;
            update_city_options(country_name)
        });
    }

    /**
    *update city list based on country from cache
    */
    function update_city_options(country_name) {
        $('#billing-city').html(city_list_cache[country_name]);
    }
    //fill_city_list($('#billing-country').val());

    $('[type="submit"]').on('click', function (e) {
        //alert("hi");
         $(".formerror").remove(); //yo line add
        //console.log("sublimt");
        var module_name = $(this).attr('name');
        //alert(module_name);return false;
        var gst_number = $('#gst_number').val();
        var continue_book_button = $(this);
        var _status = true;
        var _focus = '';

        var total_pax = parseInt($("#total_pax").val());

        var check_length = 0;
        //var first_name = $("#passenger-first-name-".val());
        //alert(first_name);return false;
        for (var i = 1; i <= total_pax; i++) {

            var first_name = $("#passenger-first-name-" + i).val().length;
            var last_name = $("#passenger-last-name-" + i).val().length;
            if (first_name < 2 || last_name < 2) {
                alert("Name should be atleast min 2 character");
                return false;
            } else {
                //alert("ela");
            }
        }
        //alert('hi');  replaced this with another function
        //change cha yaa duita function
    $("input")
      .filter("[required]:visible")
      .each(function () {
        if (this.value == "") {
          $(this)
            .addClass("invalid-ip")
            .parent()
            .append(
              "<span id='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"
            );
          window.scrollTo(0, 0);
          if (_status == true) {
            _status = false;
            _focus = this;
          }
        } else if ($(this).hasClass("invalid-ip")) {
          $(this).removeClass("invalid-ip");
          //$(this).parent().find(".formerror").hide();
        }
      });
    // $("select:required").each(function () {
    //   if (this.value == "INVALIDIP") {
    //     $(this)
    //       .addClass("invalid-ip")
    //       .parent()
    //       .append(
    //         "<span id='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>"
    //       );
    //     window.scrollTo(0, 0);
    //     if (_status == true) {
    //       _status = false;
    //       _focus = this;
    //     }
    //   } else if ($(this).hasClass("invalid-ip")) {
    //     $(this).removeClass("invalid-ip");
    //     //$(this).parent().find(".formerror").hide();
    //   }
    // });
        // $('select:required').each(function () {
        //     if (this.value == 'INVALIDIP') {
        //         $(this).addClass('invalid-ip').parent().append(
        //             "<span id='name_error'><div class='formerror'style='color:red'>This Field is mandatory</div></span>");
        //         if (_status == true) {
        //             _status = false;
        //             _focus = this;
        //         }
        //     } else if ($(this).hasClass('invalid-ip')) {
        //         $(this).removeClass('invalid-ip');
        //         //$(this).parent().find(".formerror").hide();
        //     }
        // });
        if (!validate_mobile($('#passenger-contact').val())) {
            $('#passenger-contact').addClass('invalid-ip');
            $('#invalid_mobile_msg').remove();
            $('#passenger-contact').after('<p id="invalid_mobile_msg" class="text-danger">Mobile Number Should be 10 digits and This Field is mandatory</p>');
            _status = false;
            _focus = '';
        } else {
            $('#invalid_mobile_msg').empty();
        }



        $('#pre-booking-form').find(':input[required]:visible').each(function () {
            if ($(this).is(':visible') && $(this).val().trim() == '') {
                _status = false;
            }
        });

        $('#invalid_cond_msg').remove();
        if (!$('#terms_cond1').is(':checked')) {
            //alert("hiii");
            _status = false;
            $('.clikagre').after('<p class="text-danger" id="invalid_cond_msg">Please select terms and conditions.</p>');
        }
        $('#terms_cond1').change(function () {
            if (this.checked) {

                $("#invalid_cond_msg").remove();
            }

        });

        /* function exefunction2(){
                    alert();
                    var first_name_list = [];
                    $('input[name="first_name[]"]').each(function(index){
                        first_name_list.push($(this).val());
                        // alert($('input[name="last_name[0]"]').val());
                     // alert($(this).val());
                     // alert(index);
                });var last_name_list = [];
                    $('input[name="first_name[]"]').each(function(index){
                        last_name_list.push($(this).val());
                        // alert($('input[name="last_name[0]"]').val());
                     // alert($(this).val());
                     // alert(index);
                });
                    if(hasDuplicates(first_name_list)){
                        alert();
                    }

         }*/
        /*function hasDuplicates(array) {
               var valuesSoFar = Object.create(null);
               for (var i = 0; i < array.length; ++i) {
                   var value = array[i];
                   if (value in valuesSoFar) {
                       return true;
                   }
                   valuesSoFar[value] = true;
               }
               return false;
           }*/


        if (gst_number) {
            var gst_company_name = $('#gst_company_name').val();
            var gst_email = $('#gst_email').val();
            var gst_phone = $('#gst_phone').val();
            var gst_address = $('#gst_address').val();
            var gst_state = $('#gst_state').val();
            if (gst_company_name == '') {
                //$('#gst_company_name').addClass('invalid-ip');
                $(".gst_name_error").removeClass("hide");
                _status = false;
            }
            else { $(".gst_name_error").addClass("hide"); }
            if (gst_email == '' || validate_email(gst_email) == false) {
                //$('#gst_email').addClass('invalid-ip');
                $(".gst_email_error").removeClass("hide");
                _status = false;
            } else { $(".gst_email_error").addClass("hide"); }
            if (!validate_mobile(gst_phone)) {
                //$('#gst_phone').addClass('invalid-ip');
                $(".gst_phone_error").removeClass("hide");
                _status = false;
            } else { $(".gst_phone_error").addClass("hide"); }
            if (gst_address == '') {
                //$('#gst_address').addClass('invalid-ip');
                $(".gst_address_error").removeClass("hide");
                _status = false;
            } else { $(".gst_address_error").addClass("hide"); }

            if (gst_state == 'INVALIDIP') {
                //$('#gst_state').addClass('invalid-ip');
                $(".gst_state_error").removeClass("hide");
                _status = false;
            } else { $(".gst_state_error").addClass("hide"); }
        }
        var billing_email = $('#billing-email').val();
        if (billing_email == '' || validate_email(billing_email) == false) {
            $('#billing-email').addClass('invalid-ip');
            $('#invalid_email_msg').remove();
            $('#billing-email').after('<p id="invalid_email_msg" class="text-danger">Invalid EmailId and This Field is mandatory</p>');
            _status = false;
            _focus = '';
        } else {
            $('#invalid_email_msg').empty();
        }

        if (_status == false) {
            $('.alert-content').text('Please Fill All The Data To Continue');
            $('.alert-wrapper').removeClass('hide');
            e.preventDefault();
        } else {
            var balance_check = $('.balance_check').text();

            if (balance_check == 1) {
                $("#balance-low-modal").modal("show");
                $('#passenger-confirm-modal').modal('hide');
                return false;
            }
            if (user_details_confirmed == 0 && module_name != '' && module_name != undefined && module_name != null) {

                // console.log($('input[name="last_name[0]"]').val());
                var passenger_count_check = 1;
                var first_name_list = [];
                if (module_name == 'bus') {

                } else if (module_name == 'flight') {

                    $('input[name="first_name[]"]').each(function (index) {
                        var firstname = $(this).val();
                        var lastname = $("#passenger-last-name-" + passenger_count_check).val();
                        first_name_list.push(firstname + " " + lastname);

                        passenger_count_check++;
                    });

                }
                else if (module_name == 'car') {

                }
                if (Array.isArray(first_name_list) && first_name_list.length) {
                    if (hasDuplicates(first_name_list)) {
                        $("#passenger_error").text("Same passenger name not allowed");
                        return false;
                    }
                    else {
                        $("#passenger_error").text("");
                        build_confirm_modal(module_name);
                        return false;
                    }
                }
                else {
                    build_confirm_modal(module_name);
                    return false;
                }


            }

            //console.log('success');
            /*continue_book_button.submit();
            continue_book_button.attr('disabled', true);
            $('<button class="btn btn-success" disabled="disabled"> Processing....</button>').insertAfter(continue_book_button);
            continue_book_button.hide();
            $('.alert-wrapper').addClass('hide');
            $('.alert-content').text('');*/
        }
        //return false;
    });

    //Tobin
    $('.review_tab_marker').click(function () {
        if ($(this).hasClass('review_tab_marker')) {
            $('.rondsts').removeClass('active');
            $(this).parent('.rondsts').addClass('active');
            $('.bktab2, .bktab3').fadeOut(500, function () { $('.bktab1').fadeIn(500) });
        }
    });
    //Tobin
    $('.travellers_tab_marker').click(function () {
        $(this).parent('.rondsts').addClass('active');
        $('#stepbk1').parent('.rondsts').addClass('success');
        $('.bktab1, .bktab3').fadeOut(500, function () { $('.bktab2').fadeIn(500) });
    });
    //Tobin
    $('#alreadyacnt').click(function () {
        show_alert_content('');
        if ($(this).prop("checked") == true) {
            // $('.cntgust').fadeOut(500,function(){$('.alrdyacnt').fadeIn(500)});
            $('.cntgust').hide();
            $('.alrdyacnt').show();
        }

        else if ($(this).prop("checked") == false) {
            // $('.alrdyacnt').fadeOut(500,function(){$('.cntgust').fadeIn(500)});
            $('.alrdyacnt').hide();
            $('.cntgust').show();

        }
    });
    //User Login - Balu A
    $('#continue_as_user').click(function () {
        $(".formerror").text('');
        show_alert_content('');
        var username = $('#booking_user_name').val().trim();
        var password = $('#booking_user_password').val().trim();
        if (username != '' && password != '') {
            $("#booking_user_name_error").text('');
            $("#booking_user_password_error").text('');
            var login_data = { 'username': username, 'password': password };
            $('#book_login_auth_loading_image').show();
            $.post(app_base_url + 'index.php/auth/login', login_data, function (response) {
                $('#book_login_auth_loading_image').hide();
                if (response['status'] == true) {
                    location.reload();
                } else {
                    show_alert_content(response['data']);
                }
            });
        }
        else {
            if (username == "") {
                show_alert_content("This Field is mandatory", '#booking_user_name_error');
            }
            if (password == "") {
                if ($('#booking_user_password').hasClass('invalid-ip')) {

                }
                else {
                    $('#booking_user_password').addClass('invalid-ip');
                }

                show_alert_content("This Field is mandatory", '#booking_user_password_error');
            }

        }

    });
    //Add Guest User Data - Balu A
    $('#continue_as_guest').click(function () {
        // alert();
        //alert("hi");return false;
        $("#booking_user_name_error").text('');
        $("#booking_mobile_number_error").text('');
        var username = $('#booking_user_name').val().trim();
        var mobile_number = $('#booking_user_mobile').val().trim();
        // $(".copy_fare_summery").empty();
        var count = 0;
        $(".errorlogin_msg").remove();
        $('._guest_validate').each(function () {
            if (this.value.trim() == '') {
                count++;
                // $(this).addClass('invalid-ip').parent().empty();
                $(this).addClass('invalid-ip').parent().append(
                    "<span id='name_error' class='errorlogin_msg'><div class='formerror'style='color:red'>This Field is mandatory</div></span>");
            }
        });
        if (username != '' && validate_email(username) == false) {
            // $('#booking_user_name').val('').addClass('invalid-ip').attr('placeholder', 'Invalid Email ID');
            $('#booking_user_name').addClass('invalid-ip');
            $("#booking_user_name_error").text('Invalid Email ID');
            count++;
        }
        if (mobile_number != '' && !validate_mobile(mobile_number)) {
            // $('#booking_user_mobile').val('').addClass('invalid-ip').attr('placeholder', 'Invalid Mobile Number');
            $('#booking_user_mobile').addClass('invalid-ip');
            $("#booking_mobile_number_error").text('Invalid Mobile Number');
            // $('#booking_user_mobile').addClass('invalid-ip');
            // $('#name_error').text('Invalid Mobile Number');
            count++;
        }
        if (count == 0) {
            var login_data = { 'username': username, 'mobile_number': mobile_number };
            $.post(app_base_url + 'index.php/auth/register_guest_user', login_data, function (response) {
                if (response['status'] == true) {
                    $(".copy_fare_summery").empty();
                    $('#billing-email').val(username);
                    $('#passenger-contact').val(mobile_number);
                    show_travellers_tab();
                }
            });
        }
    });
    //Guest User Data Validation
    //validation
    $('#booking_user_name').focus(function () {
        $("#booking_user_name_error").text('');
    });
    $('#booking_user_password').focus(function () {
        $(this).removeClass('invalid-ip');
    });
    $('#booking_user_password').focus(function () {
        $("#booking_user_password_error").text('');
    });
    $('._guest_validate').focus(function () {
        $(this).removeClass('invalid-ip');
        $(this).parent().find(".formerror").hide();
    });
    $('._guest_validate').blur(function () {
        if (this.value.trim() == '')
            $(this).addClass('invalid-ip');
    });
    $('.name_title').change(function (e) {
        var name_title = $(this).val().trim();
        var gender = get_gender(name_title);
        $(this).closest('div._passenger_hiiden_inputs').find('.hidden_pax_details').find('.pax_gender').val(gender);
    });
    //Balu A
    //After Continue as a guest, hide review tab and show travellers tab
    function show_travellers_tab() {

        $('.core_travellers_tab').removeClass('inactive_travellers_tab_marker').addClass('travellers_tab_marker');
        $('.travellers_tab_marker').parent('.rondsts').addClass('active');
        $('#stepbk1').parent('.rondsts').addClass('success');
        $('.core_review_tab').parent('.rondsts').removeClass('active');
        $('.core_review_tab').removeClass('review_tab_marker').addClass('inactive_review_tab_marker');//Inactive Review Tab
        $('.bktab1, .bktab3').fadeOut(500, function () { $('.bktab2').fadeIn(500) });
    }
    $('._numeric_only').on('keydown focus blur keyup change cut copy paste', function (e) {
        isNumber(e, e.keyCode, e.ctrlKey, e.metaKey, e.shiftKey);
    });
    //Balu A
    //Shows an error Message for User Login
    function show_alert_content(content, container) {
        if (typeof (container) == 'undefined') {
            container = '.alert-danger';
        }
        $(container).text(content);
        if (content.length > 0) {
            $(container).removeClass('hide');
        } else {
            $(container).addClass('hide');
        }
    }
    //Balu A
    //Returns Gender Based on Pax Title
    function get_gender(name_title) {
        var gender = 1;
        if (name_title != '') {
            name_title = parseInt(name_title);
            var male_titles = [1];
            var female_titles = [2, 3, 5];
            if ($.inArray(name_title, male_titles) != -1) {
                gender = 1;
            } else if ($.inArray(name_title, female_titles) != -1) {
                gender = 2;
            }
        }
        return gender;
    }
    function validate_mobile(number) {
        //return /^[1-9][0-9]{9}$/.test(number);
        return /^[1-9][0-9]{0,15}$/.test(number); //for 15 digit
    }

    /*
        builds passenger confirmation popup content
    */
    function build_confirm_modal(module_name) {

        var popup_html = '';
        if (module_name == 'bus') {
            popup_html = build_bus_modal_content();
        } else if (module_name == 'flight') {
            popup_html = build_flight_modal_content();
        }
        else if (module_name == 'car') {
            popup_html = build_car_modal_content();
        }
        $('#passenger-confirm-header').text('CONFIRM TRAVELLER DETAILS');
        $('#passenger-confirm-body').html(popup_html);
        var column_count = hide_empty_columns($('#passenger-confirm-table'));
        if (column_count < 2) {
            $('#passenger-confirm-modal div:first').removeClass('modal-lg large-details').addClass('modal-sm small-details');
        } else if (column_count == 2 || column_count == 3) {
            $('#passenger-confirm-modal div:first').removeClass('modal-lg large-details').addClass('modal-md medium-details');
        }
        $('#passenger-confirm-modal').modal('show');

    }

    /*
        builds passenger confirmation popup content for bus
    */
    function build_flight_modal_content() {

        var popup_html = '';
        var passenger_count = 1;

        popup_html += '<div class="table-responsive">';
        popup_html += '<table class="table" id="passenger-confirm-table">';
        popup_html += '<thead>';
        popup_html += '<tr>';
        popup_html += '<th>Passenger Name</th>';
        popup_html += '<th>Date of Birth</th>';
        popup_html += '<th>Passport Number</th>';
        popup_html += '<th>Issuing Country</th>';
        popup_html += '<th>Date of Expiry</th>';
        popup_html += '</tr>';
        popup_html += '</thead>';
        popup_html += '<tbody>';

        $('#pre-booking-form').find('.pasngr_input').each(function () {

            popup_html += '<tr>';

            popup_html += '<td>';
            popup_html += $(this).find('.name_title option:selected').text() + '. ' + $(this).find('#passenger-first-name-' + passenger_count).val() + ' ' + $(this).find('#passenger-last-name-' + passenger_count).val();
            popup_html += '</td>';


            if ($(this).find('#adult-date-picker-' + passenger_count).is(':visible')) {
                popup_html += '<td>';
                popup_html += $(this).find('#adult-date-picker-' + passenger_count).val();
                popup_html += '</td>';
            }
            else if ($(this).find('#child-date-picker-' + passenger_count).is(':visible')) {
                popup_html += '<td>';
                popup_html += $(this).find('#child-date-picker-' + passenger_count).val();
                popup_html += '</td>';
            }
            else if ($(this).find('#infant-date-picker-' + passenger_count).is(':visible')) {

                popup_html += '<td>';
                popup_html += $(this).find('#infant-date-picker-' + passenger_count).val();
                popup_html += '</td>';

            }
            else {
                popup_html += '<td>';
                popup_html += '</td>';
            }

            popup_html += '<td>';
            if ($(this).find('#passenger_passport_number_' + passenger_count).is(':visible')) {
                popup_html += $(this).find('#passenger_passport_number_' + passenger_count).val();
            } else {
                popup_html += '';
            }
            popup_html += '</td>';

            popup_html += '<td>';
            if ($(this).find('#passenger_passport_issuing_country_' + passenger_count).is(':visible')) {
                popup_html += $(this).find('#passenger_passport_issuing_country_' + passenger_count + ' option:selected').text();
            } else {
                popup_html += '';
            }
            popup_html += '</td>';

            popup_html += '<td>';
            if ($(this).find('#passenger_passport_expiry_day_' + passenger_count).is(':visible')) {
                popup_html += $(this).find('#passenger_passport_expiry_day_' + passenger_count + ' option:selected').text() + ' ' + $(this).find('#passenger_passport_expiry_month_' + passenger_count + ' option:selected').text() + ' ' + $(this).find('#passenger_passport_expiry_year_' + passenger_count + ' option:selected').text();
            } else {
                popup_html += '';
            }
            popup_html += '</td>';

            popup_html += '</tr>';

            passenger_count++;
        });
        popup_html += '</tbody>';
        popup_html += '</table>';
        popup_html += '</div>';

        popup_html += '<h6 style="color:black;">CONTACT DETAILS</h6>';
        popup_html += '<div class="table-responsive">';
        popup_html += '<table class="table">';
        popup_html += '<tbody>';
        popup_html += '<tr>';
        popup_html += '<td style="text-align: left;"> Email: ' + $('#billing-email').val(); +'</td>';
        popup_html += '<td style="text-align: left;"> Phone: ' + $('#passenger-contact').val(); +'</td>';
        popup_html += '</tr>';
        popup_html += '</tbody>';
        popup_html += '</table">';
        popup_html += '</div>';

        return popup_html;
    }


    // function to hide empty column header and body if all column data is empty
    function hide_empty_columns(table) {
        var column_count = 0;
        table.each(function (a, tbl) {
            $(tbl).find('th').each(function (i) {
                column_count++;
                var remove = true;
                var currentTable = $(this).parents('table');
                var tds = currentTable.find('tr td:nth-child(' + (i + 1) + ')');
                tds.each(function (j) { if (this.innerHTML != '') remove = false; });
                if (remove) {
                    column_count--;
                    $(this).hide();
                    tds.hide();
                }
            });
        });

        return column_count;
    }

    /*
        builds passenger confirmation popup content for bus
    */

    function build_bus_modal_content() {

        var popup_html = '';

        popup_html += '<div class="table-responsive"> ';
        popup_html += '<table class="table" id="passenger-confirm-table">';
        popup_html += '<thead>';
        popup_html += '<tr>';
        popup_html += '<th>Seat Details</th>';
        popup_html += '<th>Passenger Name</th>';
        popup_html += '<th>Age</th>';
        popup_html += '</tr>';
        popup_html += '</thead>';
        popup_html += '<tbody>';

        $('#pre-booking-form').find('.pasngrinput_secnrews').each(function () {
            popup_html += '<tr>';

            popup_html += '<td>';
            popup_html += $(this).find('.seat_number').children().html();
            popup_html += '</td>';

            popup_html += '<td>';
            popup_html += $(this).find('.name_title option:selected').text() + '. ' + $(this).find('#contact-name').val();
            popup_html += '</td>';

            popup_html += '<td>';
            popup_html += $(this).find('.age').val();
            popup_html += '</td>';

            popup_html += '</tr>';

        });

        popup_html += '</tbody>';
        popup_html += '</table">';
        popup_html += '</div">';

        return popup_html;
    }
    /*
        builds passenger confirmation popup content for car
    */

    function build_car_modal_content() {

        var popup_html = '';

        popup_html += '<div class="table-responsive"> ';
        popup_html += '<table class="table" id="passenger-confirm-table">';
        popup_html += '<thead>';
        popup_html += '<tr>';
        popup_html += '<th>Passenger Details</th>';
        popup_html += '<th>Passenger Name</th>';
        popup_html += '<th>Age</th>';
        popup_html += '</tr>';
        popup_html += '</thead>';
        popup_html += '<tbody>';

        $('#pre-booking-form').find('.pasngrinput_secnrews').each(function () {
            popup_html += '<tr>';


            popup_html += '<td>';
            popup_html += $(this).find('.name_title option:selected').text() + '. ' + $(this).find('#first_name').val() + '. ' + $(this).find('#last_name').val();
            popup_html += '</td>';

            popup_html += '</tr>';

        });

        popup_html += '</tbody>';
        popup_html += '</table">';
        popup_html += '</div">';

        return popup_html;
    }
    //Show Booking Not Allowed ALert
    //$('body').append(booking_not_allowed_alert());
    //$('#booking_not_allowed_modal').modal('show');
    function booking_not_allowed_alert() {
        var modal_content = '';
        modal_content += '<div class="modal fade" id="booking_not_allowed_modal" tabindex="-1" role="dialog">';
        modal_content += '<div class="modal-dialog" role="document">';
        modal_content += '<div class="modal-content">';
        modal_content += '<div class="modal-header">';
        modal_content += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        modal_content += '<h3 class="modal-title">Booking Not Allowed !!</h3>';
        modal_content += '</div>';
        modal_content += '<div class="modal-body">';
        modal_content += '<h4 class="text-danger">Dear customer, booking is not allowed, this is a demo site !!!!</h4>';
        modal_content += '</div>';
        modal_content += '<div class="modal-footer">';
        modal_content += '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        modal_content += '</div>';
        modal_content += '</div>';
        modal_content += '</div>';
        modal_content += '</div>';
        return modal_content;
    }
    $("#apply").click(function () {
        // alert($("#convenience_fee_gst").val());
        var pcode = $("#code").val();
        if (pcode != "") {
            $('.loading').removeClass('hide');
            $.ajax({
                type: "POST",
                url: app_base_url + 'index.php/management/promocode',
                //data: {promocode: $("#code").val(), moduletype: $("#module_type").val(), total_amount_val: $("#total_amount_val").val(), convenience_fee: $("#convenience_fee").val(),email: $("#email").val(),currency_symbol: $("#currency_symbol").val(),currency: $("#currency").val()},
                //dataType: "text", 
               //added  promo_from_country, promo_from_loc, promo_to_country, promo_to_loc
                data: { promocode: $("#code").val(), moduletype: $("#module_type").val(), total_amount_val: $("#total_amount_val").val(), convenience_fee: $("#convenience_fee").val(), convenience_fee_gst: $("#convenience_fee_gst").val(), email: $("#email").val(), currency_symbol: $("#currency_symbol").val(), currency: $("#currency").val(), extra_baggage: $("#extra_baggage_charge").text(), extra_meal: $("#extra_meal_charge").text(), extra_seat: $("#extra_seat_charge").text(),promo_from_country: $("#promo_from_country").val(), promo_from_loc: $("#promo_from_loc").val(), promo_to_country: $("#promo_to_country").val(), promo_to_loc: $("#promo_to_loc").val(), promo_for_city: $("#promo_for_city").val(), promo_to_city: $("#promo_to_city").val() },

                dataType: 'json',
                cache: false,
                success:
                    function (data) {
                        //console.log(data);
                        if (data.status == 1) {
                            $('.loading').addClass('hide');
                            $(".promo_code_discount").removeClass('hide');
                            $(".promo_discount_val").html(data.discount_value);
                            $(".grandtotal").html(data.total_amount_val);
                            $('#total_booking_amount').text(data.total_amount_val);
                            $('.total_booking_amount').html(data.total_amount_val);
                            $(".error_promocode").html('Applied Successfully');
                            $(".error_promocode").removeClass('text-danger');
                            $(".error_promocode").addClass('text-success');
                            $("#promocode_val").val(data.promocode);
                            $("#promo_code_discount_val").val(data.actual_value);
                            $("#total_amount_payment").val(data.total_amount_val);
                            $("#promo_actual_value").val(data.value);
                            var grandtotal23 = $(".grandtotal_valid").html();

                            var fv = parseInt(grandtotal23) - parseInt(data.value);
                            // alert(parseInt(grandtotal23));
                            //alert(parseInt(data.discount_value));
                            $(".grandtotal_valid").html(fv);
                        } else {
                            $('.loading').addClass('hide');
                            $(".promo_code_discount").addClass('hide');
                            $(".error_promocode").html(data.error_msg);
                            $('.error_promocode').removeClass('text-success');
                            $('.error_promocode').addClass('text-danger');
                        }

                    }
            });
            return false;
        }
        else {
            $(".error_promocode").text('Please enter a promo code');
            $('.error_promocode').removeClass('text-success');
            $('.error_promocode').addClass('text-danger');
        }
    });

    $('#code').focus(function () {
        $(".error_promocode").text('');

    });

    $("#booking_user_name").removeClass("invalid-ip");
    $("#booking_user_password").removeClass("invalid-ip");

});

function hasDuplicates(array) {
    var valuesSoFar = Object.create(null);
    for (var i = 0; i < array.length; ++i) {
        var value = array[i];
        if (value in valuesSoFar) {
            return true;
        }
        valuesSoFar[value] = true;
    }
    return false;
}

 
