$(document).ready(function () {
  function e() {
    "undefined" != typeof FB && FB.logout(function (e) {}),
      $.get(app_base_url + "index.php/auth/ajax_logout", function (e) {
        location.reload();
      });
  }
  $(".open_register").click(function () {
    $("#register-error-msg").addClass("hide");
    $("#login-status-wrapper").hide();
    $("#invalid_cond_msg").hide();
    // $('#recover-title').hide();
    $("#recover-title-wrapper").hide();
    $("form#register_user_form .validate_user_register").each(function () {
      $(this).removeClass("invalid-ip");
      $(this).parent().find(".err_msg").removeClass("invalid-ip");
    });

    $(".for_sign_in").fadeOut(500, function () {
      $(".for_sign_up").fadeIn(500);
    });
  }),
    $(".open_sign_in").click(function () {
      $("#login-status-wrapper").hide();
      $("#invalid_cond_msg").hide();
      // $('#recover-title').hide();
      $("#recover-title-wrapper").hide();
      $("form#register_user_form .validate_user_register").each(function () {
        $(this).removeClass("invalid-ip");
        $(this).parent().find(".err_msg").removeClass("invalid-ip");
      });
      $(".for_sign_up, .for_forgot").fadeOut(500, function () {
        $(".for_sign_in").fadeIn(500);
      });
    }),
    $(".forgot_pasword").click(function () {
      $("#recover_email").removeClass("invalid-ip");
      $("#forgot_pasword_email_id").text("");
      $("#recover_phone").removeClass("invalid-ip");
      $("#forgot_pasword_mobile_id").text("");
      $("#login-status-wrapper").hide();
      $("#invalid_cond_msg").hide();
      // $('#recover-title').hide();
      $("#recover-title-wrapper").hide();
      $("form#register_user_form .validate_user_register").each(function () {
        $(this).removeClass("invalid-ip");
        $(this).parent().find(".err_msg").removeClass("invalid-ip");
      });
      $(".for_sign_in").fadeOut(500, function () {
        $(".for_forgot").fadeIn(500);
      });
    }),
    $("#login_submit").on("click", function (e) {
      $("#login-status-wrapper").hide();
      e.preventDefault();
      var t = $("#email").val(),
        s = $("#password").val();
      if (t == "") {
        $("#login-status-wrapper")
          .text("Please Enter Username To Continue!!!")
          .show();
        return false;
      }
      if (s == "") {
        $("#login-status-wrapper")
          .text("Please Enter Password To Continue!!!")
          .show();
        return false;
      }
      //"" == t || "" == s ? $("#login-status-wrapper").text(message).show() : ($("#login_auth_loading_image").show(), $(".data-utility-loader", $("#myModal_1")).show(), $("#login-status-wrapper").text("Please Wait!!!").hide(), $.post(app_base_url + "index.php/auth/login/", {
      $("#login_auth_loading_image").show(),
        $(".data-utility-loader", $("#myModal_1")).show(),
        $("#login-status-wrapper").text("Please Wait!!!").hide(),
        $.post(
          app_base_url + "index.php/auth/login/",
          {
            username: t,
            password: s,
          },
          function (e) {
            $("#login_auth_loading_image").hide(),
              e.status
                ? ($("#myModal_1").hide(),
                  $(".my_account_dropdown").hide(),
                  $("#show_log").hide(),
                  window.location.reload())
                : $("#login-status-wrapper").text(e.data).show(),
              $(".data-utility-loader", $("#myModal_1")).hide();
          }
        );
    }),
    $(".register_user").bind("click", function (e) {
      e.preventDefault(),
        $("#register_user_div").provabPopup({
          modalClose: !0,
          zIndex: 10000005,
          closeClass: "closepopup",
        });
    }),
    $(".frgotpaswrd").bind("click", function (e) {
      $("#recover_phone_book").removeClass("invalid-ip");
      $("#forgot_pasword_mobile_id_booking").text("");
      $("#recover_email_book").removeClass("invalid-ip");
      $("#forgot_pasword_email_id_booking").text("");
      e.preventDefault(),
        $("#forgotpaswrdpop").provabPopup({
          modalClose: !0,
          zIndex: 10000005,
          closeClass: "closepopup",
        });
    }),
    $("#reset-password-trigger").on("click", function (e) {
      if ($("#recover_email").val() == "") {
        if ($("#recover_email").hasClass("invalid-ip")) {
        } else {
          $("#recover_email").addClass("invalid-ip");
          $("#forgot_pasword_email_id").text("Please enter email id");
        }
      } else {
        $("#recover_email").removeClass("invalid-ip");
        $("#forgot_pasword_email_id").text("");
      }

      if ($("#recover_phone").val() == "") {
        if ($("#recover_phone").hasClass("invalid-ip")) {
        } else {
          $("#recover_phone").addClass("invalid-ip");
          $("#forgot_pasword_mobile_id").text("Please enter mobile number");
        }
      } else {
        $("#recover_phone").removeClass("invalid-ip");
        $("#forgot_pasword_mobile_id").text("");
      }

      if ($("#recover_phone").val() != "" && $("#recover_email").val() != "") {
        $("#send_forgotpsw_loading_image").show();
        $(".loader-image").show();
        e.preventDefault(),
          $("#recover-title-wrapper").hide(),
          $(".data-utility-loader", $("#myModal_2")).show(),
          $.post(
            app_base_url + "index.php/auth/forgot_password/",
            {
              email: $("#recover_email").val(),
              phone: $("#recover_phone").val(),
            },
            function (e) {
              $("#send_forgotpsw_loading_image").hide();
              $(".loader-image").hide();
              e.status
                ? $("#recover-title-wrapper")
                    .removeClass("alert-danger")
                    .addClass("alert-success")
                : $("#recover-title-wrapper")
                    .removeClass("alert-success")
                    .addClass("alert-danger"),
                $("#recover-title").text(e.data),
                $("#recover-title-wrapper").show(),
                $(".data-utility-loader", $("#myModal_2")).hide();
            }
          );
        $("#recover_email").val("");
        $("#recover_phone").val("");
      }
    }),
    $("#reset-password-trigger-book").on("click", function (e) {
      if ($("#recover_email_book").val() == "") {
        if ($("#recover_email_book").hasClass("invalid-ip")) {
        } else {
          $("#recover_email_book").addClass("invalid-ip");
          $("#forgot_pasword_email_id_booking").text("Please enter email id");
        }
      } else {
        $("#recover_email_book").removeClass("invalid-ip");
        $("#forgot_pasword_email_id_booking").text("");
      }

      if ($("#recover_phone_book").val() == "") {
        if ($("#recover_phone_book").hasClass("invalid-ip")) {
        } else {
          $("#recover_phone_book").addClass("invalid-ip");
          $("#forgot_pasword_mobile_id_booking").text(
            "Please enter mobile number"
          );
        }
      } else {
        $("#recover_phone_book").removeClass("invalid-ip");
        $("#forgot_pasword_mobile_id_booking").text("");
      }

      if (
        $("#recover_phone_book").val() != "" &&
        $("#recover_email_book").val() != ""
      ) {
        e.preventDefault(),
          $("#recover-title-wrapper-book").hide(),
          $(".data-utility-loader", $("#myModal_2")).show(),
          $.post(
            app_base_url + "index.php/auth/forgot_password/",
            {
              email: $("#recover_email_book").val(),
              phone: $("#recover_phone_book").val(),
            },
            function (e) {
              e.status
                ? $("#recover-title-wrapper-book")
                    .removeClass("alert-danger")
                    .addClass("alert-success")
                : $("#recover-title-wrapper-book")
                    .removeClass("alert-success")
                    .addClass("alert-danger"),
                $("#recover-title-book").text(e.data),
                $("#recover-title-wrapper-book").show(),
                $(".data-utility-loader", $("#myModal_2")).hide();
            }
          );
      }
    }),
    $("form#register_user_form #register_user_button").click(function (e) {
      if ($("#country_code_register_element").val() == "") {
        if ($("#country_code_register").hasClass("invalid-ip")) {
        } else {
          $("#country_code_register").addClass("invalid-ip");
          $("#countrycode_error").text("Country code Field is mandatory");
        }
      } else {
        $("#country_code_register").removeClass("invalid-ip");
        $("#countrycode_error").text("");
      }

      0 == $("#register-error-msg").hasClass("hide") &&
        $("#register-error-msg").addClass("hide"),
        0 == $("#register-status-wrapper").hasClass("hide") &&
          $("#register-status-wrapper").addClass("hide"),
        e.preventDefault();
      var t = !0,
        s = "";
      $("form#register_user_form .validate_user_register").each(function () {
        "" == this.value
          ? ($(this).addClass("invalid-ip"),
            $(this).parent().find(".err_msg").addClass("invalid-ip"),
            1 == t && ((t = !1), (s = this)))
          : $(this).hasClass("invalid-ip") &&
            $(this)
              .removeClass("invalid-ip")
              .parent()
              .find(".err_msg")
              .removeClass("invalid-ip");
      }),
        0 == $("#register_tc").prop("checked")
          ? ((t = !1), $("#register_tc").addClass("invalid-ip"))
          : $("#register_tc").removeClass("invalid-ip"),
        0 == t
          ? $(s).focus()
          : ($("#loading").removeClass("hide"),
            $.post(
              app_base_url + "index.php/auth/register_on_light_box",
              $("form#register_user_form").serialize(),
              function (e) {
                var t = e.status,
                  s = e.data;
                $(".err_msg").each(function (i, obj) {
                  if ($(this).hasClass("invalid-ip")) {
                    $(this).removeClass("invalid-ip");
                  }
                });
                1 == t
                  ? ($("#register-status-wrapper").empty().html(s),
                    $("#register-status-wrapper").css("display", "block"),
                    $("#register-status-wrapper").removeClass("hide"),
                    $("#register_tc").prop("checked", !1),
                    $("#loading").addClass("hide"),
                    $(
                      'select[name="country_code"], input[name="phone"], input[name="email"], input[name="password"], input[name="confirm_password"]',
                      "form#register_user_form"
                    ).val(""))
                  : ($("#register-error-msg").empty().html(s),
                    $("#register-error-msg").removeClass("hide"),
                    $("#loading").addClass("hide"));
              }
            ));
      if (!$("#register_tc").is(":checked")) {
        $("#invalid_cond_msg").removeClass("hide");
        $("#invalid_cond_msg").show();
      } else {
        $("#invalid_cond_msg").addClass("hide");
        $("#invalid_cond_msg").hide();
      }
    }),
    $(".user_logout_button").click(function (t) {
      t.preventDefault(), e();
    });
}),
  $(document).ready(function () {
    // changes added so that the sticky navbar can be at the top whenever the notification div is hidden
    let header = $(".topsection");
    let offset = header.offset().top;
    if ($("window").scrollTop() > offset) {
      $(".topssec").css("position", "fixed").css("top", "0");
    }
    $(".sidebtn1").click(function () {
      $(".logdowndiv")
        .not($(this).children(".logdowndiv").slideToggle("fast"))
        .hide();
    }),
      $(".sidebtn1, .logdowndiv").click(function (e) {
        e.stopPropagation();
      }),
      $(document).click(function () {
        $(".logdowndiv").slideUp("fast");
      }),
      $(window).width() < 991 &&
        ($(".menu_brgr").click(function () {
          $(".sepmenus").slideToggle("fast");
        }),
        $(".menu_brgr, .sepmenus").click(function (e) {
          e.stopPropagation();
        }),
        $(document).click(function () {
          $(".sepmenus").slideUp("fast");
        })),
      // changes added so that the navbar flicker is gone and also whenever the page is scrolled the notification is hidden navbar goes on top and whenever the scrollbar is on top the notification shows
      $(window).scroll(function () {
        $(window).scrollTop() > offset
          ? $(".topssec").css("position", "fixed").css("top", "0")
          : $(".topssec").css("position", "").css("top", "");
      });
      $(".inside_alert").addClass("show"),
      $(".close_alert").click(function () {
        $(this)
          .parent(".alert_box")
          .parent(".inside_alert")
          .removeClass("show");
      });

    $("#country_code_register_element").on("change", function () {
      if ($("#country_code_register_element").val() == "") {
        if ($("#country_code_register").hasClass("invalid-ip")) {
        } else {
          $("#country_code_register").addClass("invalid-ip");
          $("#countrycode_error").text("Country code Field is mandatory");
        }
      } else {
        $("#country_code_register").removeClass("invalid-ip");
        $("#countrycode_error").text("");
      }
    });
  });

//changes removed older js that does ulto things

// $(window).bind("scroll", function () {
//   if ($(window).scrollTop() > 40) {
//     $(".section_top").addClass("fixed");
//   } else {
//     $(".section_top").removeClass("fixed");
//   }
// });

//$(window).scroll(function() {
//      $(window).scrollTop() > 40 ? ($(".section_top").addClass("fixed"), $(".fromtopmargin").addClass("set_up")) : //($(".section_top").removeClass("fixed"), $(".fromtopmargin").removeClass("set_up"))
//  }),, $(".inside_alert").addClass("show"), $(".close_alert").click(function() {
//       $(this).parent(".alert_box").parent(".inside_alert").removeClass("show")
//  })
