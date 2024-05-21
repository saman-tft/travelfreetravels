<!-- Modal for session expiry alert -->
<style>
    /* #session-alert-modal-content {
        background-color: #0BA0DC;
    } */

    #session-alert-close-btn {
        background-color: #8F53A1;
        color: white;
        font-weight: 500;
        margin: 0;
        border: solid 2px;
        border-color: #8F53A1;
    }

    #session-alert-close-btn:hover {
        background-color: transparent;
        color: #8F53A1;
    }

    #session-alert-support {
        background-color: #0BA0DC;
        color: white;
        font-weight: 500;
        margin: 0;
        border: solid 2px;
        border-color: #0BA0DC;
    }

    #session-alert-support:hover {
        background-color: transparent;
        color: #0BA0DC;
    }

    #sess-title {
        font-size: 28px;
        font-weight: 500;
        justify-content: center;
        align-items: center;
        color: black;
        font-family: monospace;
    }

    #session-alert-body {
        text-align: center;
        justify-content: center;
        align-items: center;
    }

    #session-alert-footer-container {
        display: flex;
        gap: 5px;
        justify-content: center;
        align-items: center;
    }

    #session-alert-close {
        border: none;
        margin-right: 15px;
        margin-top: 22px;
        background-color: transparent !important;
    }

    .progress-bar {
        margin-top: 10px;
        width: 100%;
        height: 15px;
        background-color: #f5f5f5;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar-inner {
        height: 100%;
        background-color: #007bff;
        transition: width 1s ease-in-out;
    }

    #message-exp {
        font-size: medium;
        color: black;
    }

    #session-alert-header {
        font-size: 22px;
    }

    #sess-modal-header {
        display: flex;
        flex-direction: row;
        background-color: #8F53A1;
        font-size: 50px;
        color: white !important;
    }

    #session-alert-clock {
        font-size: large;
    }

    #session-alert-top-message {
        color: black;
    }

    @media screen and (max-width: 365px) {
        #session-alert-footer-container {
            display: flex;
            gap: 5px;
            flex-direction: column;
        }

        #sess-title {
            font-size: 24px;
        }
    }

    @media screen and (max-width: 320px) {

        #message-exp,
        #session-alert-header {
            font-size: medium;
        }
    }

    @media screen and (max-width: 300px) {

        #message-exp,
        #session-alert-header {
            font-size: small;
        }
    }
</style>

<div id="session-alert-modal" class="modal fade" role="dialog" aria-labelledby="session-alert-modalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" id="session-alert-modal-content">
            <div class="modal-header" id="sess-modal-header">
                <div class="modal-title" id="session-alert-header"></div>
                <button type="button" data-bs-dismiss="modal" class="close" session-expired="0" id="session-alert-close">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30" height="30">
                        <path d="M18 6L6 18M6 6l12 12" stroke="white" stroke-width="2" />
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="session-alert-body">
                <div id="session-alert-clock"><i class="fa fa-clock" style="color: red;"></i> <span id="session-alert-top-message"></span></div>
                <div id="sess-title"></div>
                <div id="message-exp"></div>
                <div class="progress-bar">
                    <div class="progress-bar-inner" style="width: 0%;"></div>
                </div>
            </div>
            <div class="modal-footer" id="session-alert-footer-container">
                <a href="tel:<?php echo TFT_SUPPORT_CONTACT ?>" type="button" class="btn btn-default" session-expired="0" id="session-alert-support"><span><i class="fa fa-phone"></i></span> <?php echo TFT_SUPPORT_CONTACT ?></a>
                <a type="button" class="btn" session-expired="0" id="session-alert-close-btn"></a>
            </div>
        </div>
    </div>
</div>