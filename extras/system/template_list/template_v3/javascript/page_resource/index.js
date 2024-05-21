

$(function($) {

var check_in = db_date(7);

var check_out = db_date(10);

    $('.htd-wrap').on('click', function(e) {

        e.preventDefault();

        var curr_destination = $('.top-des-val', this).val();

        var city_id = $('.top_des_id',this).val();



 var nat_id = $('.nat_des_id',this).val();

        $('#activity_destination_search_name').val(curr_destination);

        $(".loc_id_holder").val(city_id);

        $('.holyday_selct').val(nat_id);

         $('#activity_from').val(check_in);

        $('#activity_to').val(check_out);

        $('#activity_search').submit();

    });

    $('.hpp-wrap').on('click', function(e) {

        e.preventDefault();

        var curr_destination = $('.per-pac-val', this).val();

        var city_id = $('.per-pac-id',this).val();



        $('#hotel_destination_search_name').val(curr_destination);

        $(".loc_id_holder").val(city_id);

        $('#hotel_checkin').val(check_in);

        $('#hotel_checkout').val(check_out);

        $('#hotel_search').submit();

    });

     $('.htd-wrap2c').on('click', function(e) {

        e.preventDefault();

        var curr_destination = $('.top-des-val2c', this).val();

        var city_id = $('.top_des_id2c',this).val();



        $('.hotel_destination_search_name2c').val(curr_destination);

        $('.loc_id_holder2c').val(city_id);

        $('.hotel_checkin2c').val(check_in);

        $('.hotel_checkout2c').val(check_out);

        $('#hotel_search2c').submit();

    });

    $('.activity-search').on("click",function(e){

        //alert("hiii");

        e.preventDefault();

        var curr_destination = $('.destination_name',this).val();

        //alert(curr_destination);

        //console.log("curr_destination"+curr_destination);

        var city_id = $('.destination_id',this).val();

        //alert("city_id"+city_id);

        //console.log("city_id"+city_id);

        var category_id = $('.category_id',this).val();

        $("#activity_destination_search_name").val(curr_destination);

        $(".loc_id_holder").val(city_id);

        $("#select_cate").val(category_id);

        //$("#name-search").val(curr_destination);

        $("#activity_search").submit();

    });

    // $("#owl-demo2").owlCarousel({

    //     items: 4,

    //     itemsDesktop: [991, 4],

    //     itemsDesktopSmall: [767, 2],

    //     itemsTablet: [600, 2],

    //     itemsMobile: [479, 1],

    //     nav: true,

    //     pagination: false,

    //     stagePadding: 50,

    //     autoPlay: 3000,

    //     autoPlay:true

    // });      

    $("#TopAirLine").owlCarousel({

        items:5,

        loop:true,

        margin:10,

        navigation: true,

        pagination: false,

        autoPlay:true,

        autoplaySpeed: 0,

        speed: 1000,

    autoplayTimeout:1000,

    autoplayHoverPause:true,

    autoPlay: 3000

    });

    $("#all_deal").owlCarousel({

    items : 4, 

    itemsDesktop : [1000,4],

    itemsDesktopSmall : [991,3], 

    itemsTablet: [767,3], 

    itemsMobile : [480,1], 

        navigation : true,

    pagination : false,

    autoPlay: 3000,

        autoPlay:true

    });



    $("#flight_routes").owlCarousel({

    items : 5, 

    itemsDesktop : [1000,4],

    itemsDesktopSmall : [991,3], 

    itemsTablet: [767,3], 

    itemsMobile : [480,1], 

        navigation : true,

    pagination : false,

    stagePadding: 50,

    autoPlay: 3000,

        autoPlay:true

    });

    

    $.supersized({

        slide_interval: 5000,

        transition: 1,

        transition_speed: 700,

        slide_links: 'blank',

		slides: tmpl_imgs

    })

    

});



$(document).ready(function() {

  //carousel options

  $('#quote-carousel').carousel({

    pause: true, interval: 10000,

  });

});



















