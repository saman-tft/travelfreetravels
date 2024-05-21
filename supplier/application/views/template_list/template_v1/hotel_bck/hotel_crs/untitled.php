    $('.tag_friends').on('click',function(e){
      var friend_list=<?=$friends_list?>;
      var html=' <div class="col-md-6 bdr_rt" ng-repeat="f_l in friend_list | filter:search_friends_list" ng-init="img_path=\'<?php echo $GLOBALS['CI']->template->domain_images() ?>\';no_image_path=\'<?php echo $GLOBALS['CI']->template->template_images('user-pic.png') ?>\'; ">

                       <div class="col-md-9 nopad">
                        <div class="media">
                          <div class="media-left" ng-if="f_l.image==null" >
                            <img class="friends_profile" src="{{no_image_path}}">
                          </div>
                          <div class="media-left" ng-if="f_l.image!=null" >
                            <img class="friends_profile" src="{{img_path}}{{f_l.image}}">
                          </div>
                          <div class="media-body">
                            <h6 class="profile_name">
                              <a href="<?php echo base_url() . 'index.php/user/story_board/profile_stry/'?>{{f_l.user_id}}">{{f_l.first_name}} {{f_l.last_name}}
                              </a>
                            </h6>
                           
                          </div>
                        </div>
                      </div>
                  </div>';

    });




    function activate_hotels(ele){
    var favorite = [];
            $.each($("input[name='select_hotel_id']:checked"), function(){            
                favorite.push($(this).val());
            });
            if(favorite != '' && favorite != null){
              $.ajax({
          type: "POST",
          url: "<?= site_url()."/hotels/activate_multiple_hotels" ?>",
          data: 'data='+favorite,
          dataType: "json",
          success: function(data){
            if(data.status == 1){
              window.location.href = data.success_url;
            }
          }
        });
      }else{
        alert("Please select atleast one checkbox");
      }
   }