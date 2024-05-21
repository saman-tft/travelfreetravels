<meta name="google-signin-client_id" content="<?= $client_id ?>" />
<script src="https://apis.google.com/js/platform.js" async defer></script>
<!-- Removed the previous dependency package -->
<!-- <script src="https://unpkg.com/jwt-decode/build/jwt-decode.js"></script> -->
<?php if (is_logged_in_user() == false) { ?>
     <script src="https://accounts.google.com/gsi/client?onload=onLoad" async defer></script>
     <div id="g_id_onload" data-client_id="<?= $client_id ?>" data-callback="handleCredentialResponse">
     </div>

     <script>
          function onLoad() {
               gapi.load('auth2', function () {
                    gapi.auth2.init();
               });
              
          }
           // changes  Added function to parse json and removed previous library usage
               function parseJwt(token) {
                    var base64Url = token.split('.')[1];
                    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                    var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function (c) {
                         return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                    }).join(''));

                    return JSON.parse(jsonPayload);
               }
          function handleCredentialResponse(response) {
               // Added function call to the new custom function
               const responsePayload = parseJwt(response.credential);
               var n = {};
               n.first_name = responsePayload.given_name, n.last_name = responsePayload.family_name, n.email = responsePayload.email, n.id = responsePayload.sub, $.post(app_base_url + "index.php/auth/social_network_login_auth/google", n, function (o) {
                    1 == o.status ? ($("body").css("opacity", ".1"), $(".my_account_dropdown").hide(), location.reload()) : ($("body").css("opacity", "1"), location.reload())
               })
          }

     </script>
<?php } ?>
<script>
     function onLoad() {
          alert("hii");
          gapi.load('auth2', function () {
               gapi.auth2.init();
          });
     }
</script>