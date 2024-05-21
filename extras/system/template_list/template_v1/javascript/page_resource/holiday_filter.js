  var sitemycurrency = $('#mypricecurrency').val();
 function callSearch()
   { 
    var mn             = parseFloat($("#minPrice").val());
    var mx             = parseFloat($("#maxPrice").val());          
    var count_row      = 0;
    var arrayRegion    = [];
    var countRegion    = 0;
    var arrayCountry   = [];
    var countCountry   = 0;
    var arrayCategory  = [];
    var countCategory   = 0;
    var arrayDuration  = [];
    var countDuration  = 0;
    var arrayTheme     = [];
    var countTheme     = 0;

    $('.regionCheckbox').each(function($i)
    {
     if($(this).is(":checked"))
     {
      arrayRegion[countRegion] = $(this).val();
      countRegion++;
     }
    }); 
    $('.countryCheckbox').each(function($i)
    {
     if($(this).is(":checked"))
     {
      arrayCountry[countCountry] = $(this).val();
      countCountry++;
     }
    }); 
    $('.categoryCheckbox').each(function($i)
    {
     if($(this).is(":checked"))
     {
      arrayCategory[countCategory] = $(this).val();
      countCategory++;
     }
    }); 
    $('.durationCheckbox').each(function($i)
    {
     if($(this).is(":checked"))
     {
      arrayDuration[countDuration] = $(this).val();
      countDuration++;
     }
    });  

    $('.themeCheckbox').each(function($i)
    {
     if($(this).is(":checked"))
     {
      arrayTheme[countTheme] = $(this).val();
      countTheme++;
     }
    });         

    $(".container_li").each(function($i)
    {
        /*$data_price      = parseInt($(this).attr("data-price"));
        $data_region     = parseInt($(this).attr("data-region")); //alert($data_region);
        $data_country    = parseInt($(this).attr("data-country")); //alert($data_country);
        $data_category   = parseInt($(this).attr("data-category")); //alert($data_country);
        $data_duration   = parseInt($(this).attr("data-duration")); //alert($data_duration);
        $data_theme      = $(this).attr("data-theme"); //alert($data_theme);*/
        $data_price = parseFloat($(this).data("price"));
        $data_region = parseInt($(this).data("region"));
        $data_country = $(this).data("country");
        $data_category = $(this).data("category");
        $data_duration = parseInt($(this).data("duration"));
        $data_theme = $(this).data("theme"); 

        $region_condition = $duration_condition = $theme_condition = $country_condition = $category_condition = '';
        if(countRegion > 0)
        {
         for(var $j=0;$j<countRegion;$j++)
         {
          if($data_region == arrayRegion[$j])
          {
           $region_condition = 'avail'; 
          }
         }           
        }
        else
        {
         $region_condition = 'avail';
        }

        if(countCountry > 0)
        {
         var country_condition_count = 0;
         var data_country_x = $data_country.toString();
         if(data_country_x.indexOf(",") != -1){
          data_country_x = data_country_x.split(",");
          for(var $j_x=0;$j_x<data_country_x.length;$j_x++)
          {
           for(var $j=0;$j<countCountry;$j++)
           {
            if(data_country_x[$j_x] == arrayCountry[$j])
            {
             country_condition_count++;
            }
           }
          }
          if(country_condition_count != 0 )
          {
           $country_condition = 'avail'; 
          }
         }else{
          for(var $j=0;$j<countCountry;$j++)
          {
           if(parseInt(data_country_x) == arrayCountry[$j])
           {
            $country_condition = 'avail'; 
           }
          }
         }
        }
        else
        {
         $country_condition = 'avail';
        }

        if(countCategory > 0)
        {
         var category_condition_count = 0;
         var data_category_x = $data_category.toString();
         if(data_category_x.indexOf(",") != -1){
          data_category_x = data_category_x.split(",");
          for(var $j_x=0;$j_x<data_category_x.length;$j_x++)
          {
           for(var $j=0;$j<countCategory;$j++)
           {
            if(data_category_x[$j_x] == arrayCategory[$j])
            {
             category_condition_count++;
            }
           }
          }
          if(category_condition_count != 0 )
          {
           $category_condition = 'avail'; 
          }
         }else{
          for(var $j=0;$j<countCategory;$j++)
          {
           if(parseInt(data_category_x) == arrayCategory[$j])
           {
            $category_condition = 'avail'; 
           }
          }
         }
        }
        else
        {
         $category_condition = 'avail';
        }
        if(countDuration > 0)
        {
         for(var $j=0;$j<countDuration;$j++)
         {
          if(arrayDuration[$j]==3)
          {
           $duration_min = 1;
           $duration_max = 3;
          }
          else if(arrayDuration[$j]==6)
          {
           $duration_min = 4;
           $duration_max = 6;
          }
          else if(arrayDuration[$j]==10)
          {
           $duration_min = 7;
           $duration_max = 10;
          }
          else if(arrayDuration[$j]==11)
          {
           $duration_min = 11;
           $duration_max = 100;
          }
          if(($data_duration >= $duration_min) && ($data_duration <= $duration_max))
          {
           $duration_condition = 'avail'; break;
          } 
          else
          {
           $duration_condition = 'not-avail';
          }
         }           
        }
        else
        {
         $duration_condition = 'avail';
        }
        if(countTheme > 0)
        {
         var theme_condition_count = 0;
         var data_theme_x = $data_theme.toString();
         if(data_theme_x.indexOf(",") != -1){
          data_theme_x = data_theme_x.split(",");
          for(var $j_x=0;$j_x<data_theme_x.length;$j_x++)
          {
           for(var $j=0;$j<countTheme;$j++)
           {
            if(data_theme_x[$j_x] == arrayTheme[$j])
            {
             theme_condition_count++;
            }
           }
          }
          if(theme_condition_count != 0 )
          {
           $theme_condition = 'avail'; 
          }
         }else{
          for(var $j=0;$j<countTheme;$j++)
          {
           if(parseInt(data_theme_x) == arrayTheme[$j])
           {
            $theme_condition = 'avail'; 
           }
          }
         }
        }
        else
        {
         $theme_condition = 'avail';
        }
        
            //if((($data_price >= mn) && ($data_price <= mx)) && (($data_time >= t1) && ($data_time <= t2)) && (airlinename == 'avail') && (airlinestop == 'avail') && (airlineFareType == 'avail') )
            // console.log('mx:'+$mx+',mn:'+$mn+',data_price:'+$data_price+',duration_condition:'+$duration_condition+',theme_condition:'+$theme_condition+',region_condition:'+$region_condition+',country_condition:'+$country_condition+',category_condition:'+$category_condition);
            // console.log('country_condition:'+$country_condition);

            if(($data_price >= mn) && ($data_price <= mx) && ($duration_condition == 'avail') && ($theme_condition == 'avail') && ($region_condition == 'avail') && ($country_condition == 'avail') && ($category_condition == 'avail'))
            {
             $(this).show();
             count_row++;
            }
            else
            {            
             $(this).hide();
            }
           }); 
  if(count_row==0)
  {
   $('.no_result_search').css({"display":"block"});
  }
  else
  {
   $('.no_result_search').css({"display":"none"});
  }
        /*if(count_row==1){
        	count_row = 0;
        }*/
        $('#total_records').text(count_row);
        if(parseInt(count_row)>1){
          $('#total_records_lable').text('Tours');
        }else{
          $('#total_records_lable').text('Tour');
        }
  } // callsearch

  $(function(){
    $minPrice = parseFloat($('#minPrice').val());
    $maxPrice = parseFloat($('#maxPrice').val());
    
   // console.log($minPrice); 
   // console.log($maxPrice); 
   // alert($minPrice);
   // alert($maxPrice);
   // console.log($minPrice + '#' +$maxPrice);
   $("#price-range").slider({
    range: true,
    min: $minPrice,
    max: $maxPrice,
    values: [ $minPrice, $maxPrice ],
    slide: function( event, ui ) 
    {
     // console.log(ui);
     $("#price-range-amount").html( sitemycurrency + ' ' + ui.values[0].toFixed(2) + " - " + sitemycurrency + ' ' + ui.values[1].toFixed(2));
     $("#minPrice").val(ui.values[0].toFixed(2));
     $("#maxPrice").val(ui.values[1].toFixed(2));
    }
   });
   // console.log($("#price-range").slider("values"));   
   $("#price-range-amount").html(sitemycurrency + ' ' + $("#price-range").slider("values",0).toFixed(2) + " - " + sitemycurrency + ' ' + $("#price-range").slider("values",1).toFixed(2));
   callSearch();
   var region_ul = $('ul.locationul.region').find('li');
   var country_set_ul = $('ul.locationul.country_set').find('li');
   var package_duration = $('ul.locationul.package_duration').find('li');
   var Activity_package = $('ul.locationul.Activity_package').find('li');
    region_ul.addClass('hide');
    country_set_ul.addClass('hide');
    //package_duration.addClass('hide');
    Activity_package.addClass('hide');
    $('.tour-item').each(function() {
      var that = this;
      region_ul.each(function() {
        var found = $(this).find('input[value='+$(that).data('region')+']');
        if(found.length) {
          $(this).removeClass('hide');
        }
      })
      country_set_ul.each(function() {
        var country_fl = $(that).data('country').toString();
        country_fl = country_fl.split(",");
        for (var i = 0; i < country_fl.length; i++) {
          var found = $(this).find('input[value="'+country_fl[i]+'"]');
            if(found.length) {
              $(this).removeClass('hide');
            }
        }
        
      })
      /*package_duration.each(function() {
        var found = $(this).find('input[value='+$(that).data('duration')+']');
        if(found.length) {
          $(this).removeClass('hide');
        }
      })*/
      Activity_package.each(function() {
        var country_fl = $(that).data('category').toString();
        country_fl = country_fl.split(",");
        for (var i = 0; i < country_fl.length; i++) {
          var found = $(this).find('input[value="'+country_fl[i]+'"]');
            if(found.length) {
              $(this).removeClass('hide');
            }
        }
        
      })
    })
  });

  $(document).ready(function(){

   $(".ui-slider").bind( "slidestop", function(event, ui) { callSearch(); });  
   $(".durationCheckbox").bind("click",function() { callSearch(); });
   $(".themeCheckbox").bind("click",function() { callSearch(); }); 
   $(".regionCheckbox").bind("click",function() { callSearch(); });
   $(".countryCheckbox").bind("click",function() { callSearch(); });
   $(".categoryCheckbox").bind("click",function() { callSearch(); });

   $("#reset_filters").click(function()
   {
    $('.regionCheckbox').prop("checked",false);
    $('.countryCheckbox').prop("checked",false);
    $('.categoryCheckbox').prop("checked",false);
    $('.themeCheckbox').prop("checked",false);
    $('.durationCheckbox').prop("checked",false);
    $MINPRICE = parseFloat($('#MINPRICE').val());
    $MAXPRICE = parseFloat($('#MAXPRICE').val());
    $("#price-range").slider({
     range:true,
     min:$MINPRICE,
     max:$MAXPRICE,
     values:[$MINPRICE,$MAXPRICE],
     slide:function(event,ui) 
     {
      $("#price-range-amount").html(sitemycurrency + ' ' + parseFloat(ui.values[0]).toFixed(2) + " - " + sitemycurrency + ' ' + parseFloat(ui.values[1]).toFixed(2));
      $("#minPrice").val(ui.values[0]);
      $("#maxPrice").val(ui.values[1]);                
     }
    });
    $("#price-range-amount").html(sitemycurrency + ' ' + parseFloat($("#price-range").slider("values", 0)).toFixed(2) + " - "+ sitemycurrency + ' ' + parseFloat($("#price-range").slider("values", 1)).toFixed(2));
    $("#minPrice").val( parseFloat($MINPRICE).toFixed(2));
    $("#maxPrice").val( parseFloat($MAXPRICE).toFixed(2));               
    callSearch();       
   });          
   
  
 //  $(".price-l-2-h").click(function()
 //  {   alert('hi');
 //  $("#container").jSort({
 //   sort_by: '.sortPrice', 
 //   item: 'li.container_li', 
 //   order: 'asc',
 //   is_num: true
 //  }); 
 // });
 //  $(".price-h-2-l").click(function()
 //  {   //alert('hi back');
 //  $("#container").jSort({
 //   sort_by: '.sortPrice', 
 //   item: 'li.container_li', 
 //   order: 'desc',
 //   is_num: true
 //  }); 
 // });
  $(".price-l-2-h").click(function(){
      console.log("called");
    sortingD("0","#container .container_li","#container","data-price");
 
  });
  $(".price-h-2-l").click(function(){
       console.log("called2");
    sortingD("1","#container .container_li","#container","data-price");
 
  });
});

function sortingD(v,item,div,params){
  
  var $sorted_items,
  getSorted = function(selector, attrName) {
    return $(
      $(selector).toArray().sort(function(a, b){
          var aVal = parseInt(a.getAttribute(attrName)),
              bVal = parseInt(b.getAttribute(attrName));
            if(v==0){
              return aVal-bVal;
            }else{
              return bVal-aVal;
            }          
      })
    );
  };
$sorted_items = getSorted(item,params).clone();
$(div).html($sorted_items);  
}
 