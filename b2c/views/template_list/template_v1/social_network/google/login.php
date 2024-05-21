<div id="g-signin-btn" class="g_id_signin hide" data-onsuccess="onSignIn" data-type="CredentialResponse">sdasrwe</div>
<div class="g_id_signin " data-width="308"  data-height="42" data-theme="filled_blue" data-longtitle="true" data-type="CredentialResponse" data-text="signup_with" data-onsuccess="onSignIn">asdadsadfs</div>
<script>
    function onSignIn(o) {
        var t = o.getBasicProfile();
        console.log(t), console.log("Image URL: " + t.getImageUrl()), $("body").css("opacity", ".1");
        var n = {};
        n.name = t.getName(), n.email = t.getEmail(), n.id = t.getId(), $.post(app_base_url + "index.php/auth/social_network_login_auth/google", n, function(o) {
            console.log(o.status);
            1 == o.status ? ($("body").css("opacity", ".1"), $(".my_account_dropdown").hide(), location.reload()) : ($("body").css("opacity", "1"), location.reload())
        })
    }

    function signOut() {
        
         google.accounts.id.disableAutoSelect();
       
            console.log("User signed out.")
       
       
    }
</script>