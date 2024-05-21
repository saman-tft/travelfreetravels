
            var app_base_url = "<?= base_url() ?>";
            var tmpl_img_url = '<?= $GLOBALS['CI']->template->template_images(); ?>';
<?php if (!empty($slideImageJson)) { ?>
                var slideImageJson = '<?php echo base64_encode(json_encode($slideImageJson)); ?>';
                var tmpl_imgs = JSON.parse(atob(slideImageJson));           
<?php } ?>
            var _lazy_content;
        




          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'G-2S019C1V43');
        
          $(document).ready(function(){
            $("#id").attr("content", "width=device-width, initial-scale=1")
        })
                                                var accessToken = "8484e898405d4becb83c0091285f68a2";
                                                var baseUrl = "https://api.api.ai/v1/";

                                                $(document).ready(function () {
                                                    $("#from_city").keypress(function (event) {
                                                        if (event.which == 13) {
                                                            event.preventDefault();
                                                            send();
                                                        }
                                                    });
                                                    $("#rec").click(function (event) {
                                                        switchRecognition();
                                                        // setInput();
                                                    });
                                                });
                                                var recognition;
                                               function startRecognition() {
                                                    recognition = new webkitSpeechRecognition();
                                                    recognition.onstart = function (event) {
                                                        updateRec();
                                                    };
                                                    recognition.onresult = function (event) {
                                                        var text = "";
                                                        for (var i = event.resultIndex; i < event.results.length; ++i) {
                                                            text += event.results[i][0].transcript;
                                                        }
                                                        setInput(text);
                                                        stopRecognition();
                                                    };
                                                    recognition.onend = function () {
                                                        stopRecognition();
                                                    };
                                                    recognition.lang = "en-US";
                                                    recognition.start();
                                                }
                                                function stopRecognition() {
                                                    if (recognition) {
                                                        recognition.stop();
                                                        recognition = null;
                                                    }
                                                    updateRec();
                                                }
                                                function switchRecognition() {
                                                    if (recognition) {
                                                        stopRecognition();
                                                    } else {
                                                        startRecognition();
                                                    }
                                                }
                                                function setInput(text) {
                                                    $("#input_speech").val(text);                                                   
                                                    var from_city = text.split(" to");
                                                    if (typeof (from_city[1]) != 'undefined' && from_city[1].indexOf(' on') >= -1) {
                                                        var to_city = from_city[1].split(" on");
                                                    } else {
                                                        var to_city = [from_city[1], ''];
                                                    }
                                                    if (typeof (to_city[1]) != 'undefined' && to_city[1].indexOf(' for') != -1) {
                                                        var ddate = to_city[1].split(" for");
                                                    } else {
                                                        
                                                        if (typeof (to_city[1]) != 'undefined') {
                                                            var removed_space_date = to_city[1].trim();

                                                            var new_date = removed_space_date.split(" ");

                                                            var ddate = [new_date[1] + ' ' + new_date[0]];

                                                        } else {
                                                            var d = new Date();
                                                            var strDate = (d.getDate() + 1) + "-" + (d.getMonth() + 1);

                                                            var ddate = [strDate, ''];
                                                        }

                                                    }

                                                    if (typeof (ddate[1]) != 'undefined' && ddate[1].indexOf(' adult') != -1) {
                                                        var adult_value = ddate[1].split("adult");
                                                    } else {
                                                        var adult_value = ["1"];
                                                    }

                                                    if (typeof (adult_value[1]) != 'undefined' && adult_value[1].indexOf(' child') != -1) {
                                                        var child_value = adult_value[1].split(" child");
                                                    } else {
                                                        var child_value = ["0"];
                                                    }
                                                    if (typeof (child_value[1]) != 'undefined' && child_value[1].indexOf(' infant') != -1) {
                                                        var infant_value = child_value[1].split(" infant");
                                                    } else {
                                                        var infant_value = ["0"];
                                                    }
                                                    if ($.trim(to_city[0]) != '' && $.trim(from_city[0]) != '') {
                                                        var from_city_value = update_city($.trim(from_city[0]), 'from', 'from_loc_id_val');
                                                        var to_city_value = update_city($.trim(to_city[0]), 'to', 'to_loc_id_val');

                                                        $("#flight_datepicker1").val(ddate[0] + "-2018");
                                                        $("#OWT_adult").val(adult_value[0]);
                                                        $("#OWT_child").val(child_value[0]);
                                                        $("#OWT_infant").val(infant_value[0]);

                                                        
                                                        setTimeout(function () {
                                                            $("#flight-form-submit").click();
                                                        }, 5000);
                                                    } else {
                                                        alert("Please Try agin with proper input data");
                                                    }
                                                }
                                                function updateRec() {
                                                    $("#rec").html(recognition ? "<img style='width: 14px; padding-top: 2px;' src='<?php echo $GLOBALS['CI']->template->template_images('mike_red.png'); ?>' alt='Book a tour to India'>" : "<img style='width: 14px; padding-top: 2px;' src='<?php echo $GLOBALS['CI']->template->template_images('mike.png'); ?>' alt='Book a tour to India'>");
                                                }
                                                function update_city(input_data, id, val) {
                                                    var search_data = input_data.replace(" ", "_");

                                                    $.ajax({
                                                        type: "POST",
                                                        url: 'https://localhost/travelomatix/index.php/ajax/get_airport_code_list_for_voice_speach/' + search_data,
                                                        success: function (data) {
                                                            var data_arrange = data.split("|");

                                                            $("#" + id).val($.trim(data_arrange[0]));
                                                            $("#" + val).val($.trim(data_arrange[1]));
                                                        },
                                                    });
                                                }
                                               function send() {
                                                    var text = $("#input").val();
                                                    $.ajax({
                                                        type: "POST",
                                                        url: baseUrl + "query?v=20150910",
                                                        contentType: "application/json; charset=utf-8",
                                                        dataType: "json",
                                                        headers: {
                                                            "Authorization": "Bearer " + accessToken
                                                        },
                                                        data: JSON.stringify({query: text, lang: "en", sessionId: "somerandomthing"}),

                                                        success: function (data) {
                                                            setResponse(JSON.stringify(data, undefined, 2));
                                                        },
                                                        error: function () {
                                                            setResponse("Internal Server Error");
                                                        }
                                                    });
                                                    setResponse("Loading...");
                                                }

                                                function setResponse(val) {
                                                    $("#response").text(val);
                                                }
        $(document).ready(function(){
            $('#keep_mail_sub').click(function (e) {
                $("#keep_mail_sub_error").text('');
                $("#sub_msg").text('');
        // alert();
        e.preventDefault();
        var input_text=$("#email_id").val();
            var mailformat =/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(input_text);            
            if(!mailformat)
            {
                $("#keep_mail_sub_error").text('Enter Valid email address!');
               return false;
            }
        $.ajax({
            url:app_base_url+'index.php/general/save_keep_email',
            type:'POST',
            data:{'email_id':input_text},
            success:function(msg){
                if(msg.status == true)
                {
                    $("#sub_msg").css("color", "green");
                    $('#sub_msg').text(msg.message);
                }
                else
                {
                    $("#sub_msg").css("color", "red");
                    $('#sub_msg').text(msg.message);
                }
            },
            error:function(){
            }
         }) ;

    });
        })
        $(window).scroll(function(){
            if ($("input").is(":focus")) {
            $('.hasDatepicker').blur();
            }            
        });
        
               $(".videoimg").click(function(){
                $(this).addClass("videoimgactive")  ; 
                })
            

               $(".destiimg").click(function(){
                $(this).addClass("destiimgactive")  ; 
                })
            

               $(".bookimg").click(function(){
                $(this).addClass("bookimgactive")  ; 
                })
            
               $(".qtvimg").click(function(){
                 $(this).addClass("qtvimgactive")  ; 
                })
            
               $(".videotext").click(function(){
                $('.linkNamevideo').addClass("videotextactive")  ;                
                })
            
           $(function(){
               $(".destitext").click(function(){
                $('.linkNamedesti').addClass("destitextactive")  ; 
               
                });
           });
            

               $(".booktext").click(function(){
                $('.linkNamebook').addClass("booktextactive")  ; 
                })
            

               $(".qtvtext").click(function(){
                 $(".linkNameqtv").addClass("qtvtextactive")  ; 
                })
            
                $(document).ready(function () {
                    $('body').on('dragstart', function () {
                        return false;
                    });
                });
            

            
    $(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 6,
        autoplay: true,
        autoplaySpeed: 0,
        speed: 1000,
        cssEase: "linear",
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 3
            }
        }]
    });
}); 



    $(document).ready(function(){
            $('#keep_mail_sub').click(function (e) {
                $("#keep_mail_sub_error").text('');     
        e.preventDefault();
        var input_text=$("#email_id").val();          
            var mailformat =/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/.test(input_text);            
            if(!mailformat)
            {
                $("#keep_mail_sub_error").text('Enter Valid email address!');
               return false;
            }
        $.ajax({
            url:app_base_url+'index.php/general/save_keep_email',
            type:'POST',
            data:{'email_id':input_text},
            success:function(msg){

                if(msg.status == true)
                {
                    $("#sub_msg").css("color", "green");
                    $('#sub_msg').text(msg.message);
                }
                else
                {
                    $("#sub_msg").css("color", "red");
                    $('#sub_msg').text(msg.message);
                }
            },
            error:function(){
            }
         }) ;
    });
        })
  