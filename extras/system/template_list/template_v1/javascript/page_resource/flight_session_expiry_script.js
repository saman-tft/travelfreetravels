$(document).ready(function () {
    window.check_session_time_out = function (search_hash, start_time, search_session_alert_expiry = "", search_session_expiry = "", set_alert = true, sub_start_time = 5000) {
        if (search_hash != '' && search_hash != null && search_hash != undefined) {
            let progress_bar = 0;
            start_time = start_time - parseInt(sub_start_time / 1000);
            setInterval(function () {
                start_time--;
                if (parseInt(search_session_alert_expiry) == start_time && set_alert) {
                    $('#session-alert-header').html("");
                    $('#session-alert-header').html('Session Timeout Notification');
                    $('#session-alert-header').show();
                    $('#session-alert-support').show();
                    $('#session-alert-close-btn').html("");
                    $('#session-alert-close-btn').html('OK');
                    $('#message-exp').hide();
                    $('.progress-bar').show();
                    $('#session-alert-top-message').html("");
                    $('#session-alert-top-message').html("Your search session will expire in");
                    $('#session-alert-top-message').show();
                    $('#session-alert-clock').show();
                    $('#passenger-confirm-modal').modal('hide');
                    $('#session-alert-modal').modal('show');
                    progress_bar = 1;
                } else if ((start_time <= 0) && $('#session-alert-close').attr('session-expired') == 0) {
                    $('#session-remaining-time').hide();
                    $('#session-alert-close, #session-alert-close-btn').attr('session-expired', 1);
                    $('#session-alert-header').html("");
                    $('#session-alert-header').html('Session Timeout Notification');
                    $('#session-alert-header').show();
                    $('#session-alert-clock').hide();
                    $('#session-alert-support').show();
                    $('#sess-title').show();
                    $('#sess-title').html("");
                    $('#sess-title').html('SESSION EXPIRED');
                    $('#session-alert-close-btn').html("");
                    $('#session-alert-close-btn').html('CHECK LATEST FARES');
                    $('.progress-bar-inner').attr('style', 'width:' + 0 + '%');
                    $('.progress-bar').hide();
                    $('#message-exp').html("");
                    $('#message-exp').html("Please search again to continue.");
                    $('#message-exp').show();
                    $('#passenger-confirm-modal').modal('hide');
                    $('#session-alert-modal').modal('show');
                    progress_bar = 0;
                } else if (start_time > 0 && start_time < 5) {
                    $('.bookallbtn').on('click', function (e) {
                        e.preventDefault();
                        $(this).text('Processing');
                    });
                }

                if ($('#session-alert-close').attr('session-expired') == 0) {
                    $('#session-remaining-time').html("");
                    $('#session-remaining-time').html("<span style='color:#0BA0DC;'><i class='fa fa-alarm-clock'></i></span> You have <span style='color:#0BA0DC'>" + formatTime(start_time) + "</span> to complete the booking");
                    $('#session-remaining-time').show();
                }
                if (progress_bar == 1) {
                    $('#sess-title').html("");
                    $('#sess-title').html(formatTime(start_time));
                    $('.progress-bar-inner').attr('style', 'width:' + start_time * (100 / search_session_alert_expiry) + '%');
                }
            }, 1000);
        }
    }

    function formatTime(seconds) {
        var minutes = Math.floor(seconds / 60);
        var remainingSeconds = seconds - minutes * 60;
        return minutes + " min " + (remainingSeconds < 10 ? "0" : "") + remainingSeconds + " secs";
    }

    $('#session-alert-close, #session-alert-close-btn').on('click', function () {
        $('#session-alert-modal').modal('hide');
        if ($(this).attr('session-expired') == 1) {
            window.location = redirect_url_after_session;
        }
    });

    $(document).click(function (e) {
        if ($(e.target).hasClass('modal') && $('#session-alert-modal').is(':visible')) {
            $('#session-alert-modal').modal('hide');
            if ($('#session-alert-close, #session-alert-close-btn').attr('session-expired') == 1) {
                window.location = redirect_url_after_session;
            }
        }
    });

    if (session_time_out_function_call == 1) {
        setTimeout(function () {
            check_session_time_out(search_hash, start_time, search_session_alert_expiry, search_session_expiry, true, sub_start_time);
        }, sub_start_time);
    }
});
