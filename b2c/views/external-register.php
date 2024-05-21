<?php 
$___favicon_ico = $GLOBALS ['CI']->template->domain_images('favicon/favicon.ico');
$addr = $this->custom_db->single_table_records('api_country_list', '*');
$addr = $addr['data'];
$referral = $_GET['referral'];


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= $___favicon_ico ?>" type="image/x-icon" />
    <title>Register</title>

    <!-- added google tag manager -->
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-K4PP7X5J');
    </script>
    <!-- End Google Tag Manager -->
    <style>
        /* Start of Universal styles*/
        * {
            font-family: monospace, serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;

        }
        
            .highlighted {
      background-color: lightblue;
            cursor: pointer;
        }


        /* End of universal styles */

        /* Start of styling the form box */
        .register__main {
            min-height: 100vh;
            min-width: 100vw;
            display: grid;
            place-content: center;
        }

        .register__main-container {
            display: flex;

            align-items: stretch;
            backdrop-filter: blur(15px);
            min-width: 80vw;
            background: linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
            border: 5px solid #8f53a1;
            box-shadow: 10px 10px 20px purple;
        }

        .register__main-container-right {
            flex: 0.7;
        }

        .register__main-container-left {
            flex: 0.3;
        }





        .register__form {
            margin: 0em 2em 2em 2em;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

        }

        .register__form-name,
        .registration__selection,
        .register__credentials,
        .register__password {
            min-width: 100%;
            display: Flex;
            justify-content: space-between;
            align-items: center;

        }

        .register__credentials,
        .register__password {
            justify-content: space-around;
        }

        .page__title {
            font: inherit;
            color: white;
            font-weight: 700;
            font-size: 28px;
            text-align: center;
            margin-top: 20px;
        }

        /* End of  form-box styling */

        /* Image styling */

        .register__main-container-left-image img {
            max-height: 500px;
        }

        .register__logo__container img {
            max-height: 170px;

        }

        /* End of image styling */

        /*Start of Input boxes styling */
        /* Remove default styling and adjust the layout of the actual input box */
        .input-box input {
            width: 100%;
            height: 50px;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1em;
            color: #fff;
            padding: 0 35px 0 5px;
        }



        .input-box {
            position: relative;
            width: 200px;
            margin: 30px 0;
            border-bottom: 2px solid #fff;
            background: transparent;
        }

        /* For the individual column with only two inputs */
        .phone__input,
        .email__input,
        .selection__input {
            width: 300px;

        }

        .registration__selection .input-box:after {
            position: absolute;
            content: "";
            top: 30px;
            right: 10px;
            width: 0;
            height: 0;
            border: 7px solid transparent;
            border-color: white transparent transparent transparent;
            z-index:-1;
        }




        /* Adjust position of the label */
        .input-box label {
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            font-size: 1em;
            color: #fff;
            pointer-events: none;
            transition: .5s;
        }

        /* When in focus or clicked move the label to the top */
        .input-box input:focus~label {
            top: -5px;

        }
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus,
        select:-webkit-autofill,
        select:-webkit-autofill:hover,
        select:-webkit-autofill:focus {
            border: none;
            -webkit-text-fill-color: white;
            -webkit-box-shadow: 0 0 0px 1000px transparent inset;
            transition: background-color 5000s ease-in-out 0s;
        }


        /* End of styling input boxes */


        /* Start of styling the buttons */
        .button__container {
            display: grid;
            place-content: center;
        }

        .button__container button {
            margin-top: 1em;
            padding: 10px 30px;
            border: 1px solid #8F53A1 inset;
            box-shadow: 2px 3px 3px purple;
            background-color: #0BA0DC;
            color: white;
            font-size: 18px;
            font-weight: 800;
            transition: all 0.4s ease-in-out;
        }
         .button__container button:hover{
            cursor:pointer;
            background-color: purple;
            
         }


        /* End of styling the buttons */

        /* Start of Error Styling */
        .first_name,
        .middle_name,
        .last__name,
        .register__phone,
        .register__email,
        .password,
        .register__referral,
        .confirm__password {
            position: relative;
        }

        .error-message {
            position: absolute;
            top: 90px;
            left: 0;
            color: red;
            font: inherit;
            font-size: 13px;
            font-weight: 600;
            width: 200px;
        }

        .registration__selection .error-message,
        .register__password .error-message {
            width: 300px;
        }

        /* End of Errors Styling */


        /* Start of styling the options?suggestions?whatever*/
        .country__code-selection,
        .nationality__selection {
            position: relative;

        }

        /* Style for the options div */
        .selection__content {
            position: absolute;
            left: 0;
            top: 95px;
            display: none;
            position: absolute;
            background: linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
            background-size: cover;
            background-repeat: no-repeat;
            width: 300px;
            color: white;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 100;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            overflow-y: auto;
            max-height: 280px;


        }


        /* Style for the individual items */
        .selection__content a {
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 2px solid white;
            z-index: 100;

        }

        /* Style for the individual items on hover */
        .selection__content a:hover {
            background-color: lightblue;
            cursor: pointer;
        }

        /* styling the scrollbars */
        .selection__content::-webkit-scrollbar {
            width: 10px;
        }

        /* Customize the scrollbar thumb style */
        .selection__content::-webkit-scrollbar-thumb {
            background: #8F53A1;
            border-radius: 10px;
        }

        /* Customize the scrollbar track style */
        .selection__content::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }

        /* Added for transitions */
        .selection__content.show {
            display: block;
            opacity: 1;
        }

        .register__logo__container-small {
            display: none;
        }

        .register__main-container-right a {
            text-decoration: none;
            font: inherit;
            color: white;
        }

        .register__main-container-right .goBack {
            position:fixed;
            top:0;
            right:40px;
            background-color: transparent;
            border: 1px solid purple;
            outline: none;
            text-decoration: none;
            box-shadow: 5px 5px 5px purple;
            color: white;
            padding: 10px 50px;
            font-size: 18px;
        }
                    .register__main-container-right .goBack {
            position: fixed;
            top: 20px;
            right: 40px;
            text-align: center;
            background-color: transparent;
            border: 1px solid purple;
            outline: none;
            text-decoration: none;
            box-shadow: 5px 5px 5px purple;
            color: white;
            padding: 10px 50px;
            font-size: 18px;

        }

            .title__container {
                display: flex;
                justify-content: space-between;
              margin-bottom: 5em;;
            }


        @media (max-width: 280px) {
            
            .register__main {
                min-height: 100vh;
                min-width: 100vw;
                max-width: 100vw;

            }
                .register__main-container-right .goBack {
                position: fixed;
                top: 10px;
                right: -70px;
                text-align: center;
                background-color: transparent;
                outline: none;
                text-decoration: none;
                box-shadow: 2px 2px 2px #0ba0dc;
                color: white;
                padding: 10px 20px;
                font-size: 18px;

            }

            .title__container {
                display: flex;
                justify-content: space-between;
                margin: 0;
            }


            .register__main-container {
                border: none;
                box-shadow:none;
            }

            .register__main-container-left {
                display: none;
            }

            .register__form-name,
            .registration__selection,
            .register__credentials,
            .register__password {
                min-width: 100%;
                display: Flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;

            }

            .input-box {
                position: relative;
                width: 300px;
                margin: 30px 0;
                border-bottom: 2px solid #fff;
                background: transparent;
            }

            .error-message {

                width: 300px;
            }




            .register__main-container-right {
                background-image: url('http://drive.google.com/uc?export=view&id=1eshv_H1isLsIfglyF3dCZHXt0PTI_1dE'), linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
                background-position: center, center;
                background-size: contain, cover;
                background-position-y: center;
                background-repeat: no-repeat;

            }

        

            .page__title {
                font: inherit;
                color: white;
                font-weight: 700;
                font-size: 18px;
                text-align: center;
                margin-top: 20px;
            }


        }

        @media only screen and (min-width: 281px) and (max-width: 480px) {
            .register__main {
                min-height: 100vh;
                min-width: 100vw;
                max-width: 100vw;

            }
            .register__main-container-right .goBack {
                position: fixed;
                top: 10px;
                right: 5px;
                text-align: center;
                background-color: transparent;
                outline: none;
                text-decoration: none;
                box-shadow: 5px 4px 8px black;
                color: white;
                padding: 10px 20px;
                font-size: 18px;

            }

            .title__container {
                display: flex;
                justify-content: space-between;
                margin: 0;
            }


       .register__main-container {
                border: none;
                box-shadow:none;
            }


            .register__main-container-left {
                display: none;
            }

            .register__form-name,
            .registration__selection,
            .register__credentials,
            .register__password {
                min-width: 100%;
                display: Flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;

            }

            .input-box {
                position: relative;
                width: 300px;
                margin: 30px 0;
                border-bottom: 2px solid #fff;
                background: transparent;
            }

            .error-message {

                width: 300px;
            }



            .register__main-container-right {
                background-image: url('http://drive.google.com/uc?export=view&id=1eshv_H1isLsIfglyF3dCZHXt0PTI_1dE'), linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
                background-position: center, center;
                background-size: contain, cover;
                background-position-y: center;
                background-repeat: no-repeat;
            }


            .page__title {
                font: inherit;
                color: white;
                font-weight: 700;
                font-size: 18px;
                text-align: center;
                margin-top: 20px;
            }

        }

        @media only screen and (min-width: 480px) and (max-width: 767px) {



            .register__main {
                min-height: 100vh;
                min-width: 100vw;
                padding: 0;

            }

            .register__main-container-left {
                display: none;
            }

            .register__form-name,
            .registration__selection,
            .register__credentials,
            .register__password {
                min-width: 100%;
                display: Flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: center;

            }

            .input-box {
                position: relative;
                width: 80vw;
                margin: 30px 0;
                border-bottom: 2px solid #fff;
                background: transparent;
            }

            .error-message {

                width: 80vw;
            }


            .register__main-container-right {
                background-image: url('<?php echo base_url('/images/mascot-light.png')?>'), linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
                background-position: center, center;
                background-size: contain, cover;
                background-position-y: center;
                background-repeat: no-repeat;
                min-width: 100vw;
            }

            .title__container {
                display: flex;
                justify-content: space-between;
            }

            .page__title {
                font: inherit;
                color: white;
                font-weight: 700;
                font-size: 18px;
                text-align: center;
                margin-top: 20px;
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 834px) {
            .register__main {
                display: grid;
                place-content: center;
            }
                        .register__main-container-right {
                    background-image: url('<?php echo base_url('/images/mascot-light.png')?>'), linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
                background-position: center, center;
                background-size: contain, cover;
                background-position-y: center;
                background-repeat: no-repeat;
                min-width: 100vw;
            }
            


            .body {
                min-height: 100vh;
                min-width: 100vw;
            }

            .register__main-container-left {
                display: none;

            }

            .register__main-container-right {
                flex: 1;
                
            }

            .title__container {
                display: flex;
                justify-content: space-between;
            }

            .page__title {
                font: inherit;
                color: white;
                font-weight: 700;
                font-size: 24px;
                text-align: center;
                margin-top: 20px;
            }

            .register__form {
                max-height: 600px;
            }

            .input-box {
                position: relative;
                width: 170px;
                margin: 30px 0;
                border-bottom: 2px solid #fff;
                background: transparent;
            }

            .selection__content {
                width: 170px;




            }

            .error-message {

                width: 170px;
            }



            .input-box label {
                font-size: 12px;
            }



        }

        @media only screen and (min-width: 834px) and (max-width:1020px) {

                               .register__main-container-right {
                      background-image: url('<?php echo base_url('/images/mascot-light.png')?>'), linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
                background-position: center, center;
                background-size: contain, cover;
                background-position-y: center;
                background-repeat: no-repeat;
                min-width: 100vw;
            }
            .register__form-name .input-box,
            .register__password .input-box {
                width: 170px;
            }


            .register__main {
                display: grid;
                place-content: center;
            }

            .input-box {
                width: 200px;
            }

            .selection__content {
                width: 200px;




            }

            .error-message {

                width: 200px;
            }



            .body {
                min-height: 100vh;
                min-width: 100vw;
            }

            .register__main-container-left {
                display: none;

            }

            .register__main-container-right {
                flex: 1;
            }

            .title__container {
                display: flex;
                justify-content: space-between;
            }

            .page__title {
                font: inherit;
                color: white;
                font-weight: 700;
                font-size: 24px;
                text-align: center;
                margin-top: 20px;
            }

            .register__form {
                max-height: 600px;
            }


            .input-box label {
                font-size: 16px;
            }
        }

        @media only screen and (min-width:1022px) and (max-width:1184px) {
                                          .register__main-container-right {
                     background-image: url('<?php echo base_url('/images/mascot-light.png')?>'), linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
                background-position: center, center;
                background-size: contain, cover;
                background-position-y: center;
                background-repeat: no-repeat;
                min-width: 100vw;
            }
            .register__main {
                display: grid;
                place-content: center;
            }

            .input-box {
                width: 200px;
            }

            .selection__content {
                width: 200px;




            }

            .error-message {

                width: 300px;
            }



            .body {
                min-height: 100vh;
                min-width: 100vw;
            }

            .register__main-container-left {
                display: none;

            }

            .register__main-container-right {
                flex: 1;
            }

            .title__container {
                display: flex;
                justify-content: space-between;
            }

            .page__title {
                font: inherit;
                color: white;
                font-weight: 700;
                font-size: 24px;
                text-align: center;
                margin-top: 20px;
            }

            .register__form {
                max-height: 600px;
            }


            .input-box label {
                font-size: 16px;
            }

        }

        @media only screen and (min-width:1185px) and (max-width:1285px) {
            .small-logo {
                display: none;
            }


            .register__logo__container img {
                max-width: 270px;
            }

            .register__main-container-left-image img {
                height: 350px;
            }

            .input-box {
                width: 175px;
            }

            .selection__content {
                width: 175px;
            }

            .error-message {

                width: 175px;
            }

            .input-box label {
                font-size: 12px;
            }


        }

        @media (min-width: 1285px) {
            .small-logo {
                display: none;
            }
        }
    </style>
</head>

<body style="background: linear-gradient(120deg, purple, #8f53a1, #0ba0dc);background-size:cover;background-repeat:no-repeat;">
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4PP7X5J"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
     <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11441192494"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'AW-11441192494');
</script>

    <main class="register__main">
        <article class="register__main-container">
            <section class="register__main-container-left">
                <div class="register__logo__container">
                    <a href="<?php echo base_url('/'); ?>"> <img src="          <?php echo base_url('/images/companylogo.png')?>" alt="Travel Free Travels" height="300px" width="300px" /></a>
                </div>
                <div class="register__main-container-left-image">
                    <img src="<?php echo base_url('/images/mascot-main.png')?>
                    " alt="Travel Free Travels" />
                </div>
            </section>
            <section class="register__main-container-right">
                <div class="title__container">
                    <div class="small-logo">
                        <a href="<?php echo base_url('/'); ?>"> <img src="<?php echo base_url('/images/companylogo.png')?>" alt="Travel Free Travels" width=200 /></a>
                    </div>         
                       <a href = "<?php echo base_url('/login')?>"class="goBack">
                        <img src="https://drive.google.com/uc?export=download&id=1KKheCLp9fdYS0TKl7UnhUhLfBOOEt7rt" alt="arrow" style="display:none;">
                        Login

                    </a>
                    <div class="register__logo__container-small">
                        <img src="<?php echo base_url('/images/mascot-main.png')?>" alt="Travel Free Travels" />
                    </div>
                </div>
                <div class="page__title">
                    Register with Travel Free Travels
                </div>
                <div class="register__form__container">
                    <form autocomplete="off" class="register__form" action="<?php echo base_url('/registeruser') ?>" method="post" onsubmit="return validateForm();" >
                        <input type="hidden" name="_autocomplete" value="false">
                        <div class="register__form-name">
                            <div class="first_name">
                                <div class="input-box">
                                    <input type="text" name="firstname" id="firstname" value="<?php echo set_value('firstname'); ?>" autocomplete="off" required>
                                    <label for="firstname"> First Name</label>
                                </div>
                                <span class="error-message" id="firstNameError"><?php echo form_error('firstname'); ?></span>
                            </div>
                            <div class="middle_name ">
                                <div class="input-box">
                                    <input type="text " name="middlename" id="middlename" value="<?php echo set_value('middlename'); ?>" autocomplete="off">
                                    <label for="middlename">Middle Name</label>
                                </div>
                                <span class=" error-message" id="middleNameError"><?php echo form_error('middlename'); ?></span>
                            </div>
                            <div class="last__name">
                                <div class="input-box">
                                    <input type="text" name="lastname" id="lastname" value="<?php echo set_value('lastname'); ?>" autocomplete="off" required>
                                    <label for="lastname">Last Name</label>
                                </div>
                                <span class=" error-message" id="lastNameError"><?php echo form_error('lastname'); ?></span>
                            </div>
                        </div>
                        <div class="registration__selection">
                            <div class="country__code-selection">
                                <div class="selection__input input-box">
                                    <input type="text" name="countrycode" id="countrycode" value="<?php echo set_value('countrycode'); ?>" onkeyup="filterCountries()" onfocus="showFullCountryList()" autocomplete="off" required>
                                    <label for="countrycode">Select Country Code</label>

                                </div>
                                <span class="countryCodeError error-message" id="countryCodeError"></span>
                                <div class="selection__content" id="countryDropdown">
                                    <?php
                                    foreach ($addr as $key => $value) {
                                        echo '<a>' . $value['name'] . ' ' . ' ' . '+' . $value['country_code'] . '</a>';
                                    }
                                    ?>

                                </div>

                            </div>
                            <div class="nationality__selection">
                                <div class="selection__input input-box">
                                    <input type="text" name="nationality" value="<?php echo set_value('nationality'); ?>" id="nationality" onkeyup="filterNationalities()" onfocus="showFullNationalityList()" autocomplete="off" required>
                                    <label for="nationality">Select Nationality</label>

                                </div>
                                <span class="nationalityError error-message" id="nationalityError"></span>
                                <div class="selection__content" id="nationalityDropdown">
                                    <?php
                                    foreach ($addr as $key => $value) {
                                        echo '<a>' . $value['nationality'] . '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="register__credentials">
                            <div class="register__email">
                                <div class="input-box email__input">

                                    <input type="email"  role="presentation" name="email" value="<?php echo set_value('email'); ?>" id="email" autocomplete="new-password" required>
                                    <label for="email">Email</label>
                                </div>
                                <span class=" error-message" id="emailError"><?php echo form_error('email'); ?></span>
                            </div>
                            <div class="register__phone ">
                                <div class="input-box phone__input">

                                    <input type="tel" name="phone" value="<?php echo set_value('phone'); ?>" id="phone" autocomplete="off" required>
                                    <label for="phone">Phone Number</label>
                                </div>
                                <span class=" error-message" id="phoneNumberError"><?php echo form_error('phone'); ?></span>
                            </div>
                        </div>
                        <div class="register__password">
                            <div class="password">
                                <div class="input-box">
                                    <input type="password" name="password" id="password" autocomplete="new-password" required>
                                    <label for="password">Password</label>
                                </div>
                                <span class=" error-message"><?php echo form_error('password'); ?></span>
                            </div>
                            <div class="confirm__password">
                                <div class="input-box">
                                    <input type="password" name="confirmpassword" id="confirmpassword" autocomplete="off" required>
                                    <label for="confirmpassword">Confirm Password</label>
                                </div>
                                <span class=" error-message" id="confirmPasswordError"><?php echo form_error('confirmpassword'); ?></span>
                            </div>
                            <div class="register__referral">
                                <div class="referral__code">
                                    <div class="input-box">
                                        <input type="referral" name="referral" id="referral" value="<?php echo isset($referral) ? $referral : set_value('referral'); ?>" autocomplete="off">
                                        <label for="referral">Referral Code</label>
                                    </div>
                                </div>
                                <span class="error-message"><?php echo $data;
                                                            $data = " " ?></span>
                            </div>


                        </div>
                        <div class="button__container">
                            <button class="submit">Register</button>

                        </div>
                    </form>

                </div>
            </section>
        </article>
    </main>
    <script>
        function hideErrorMessages() {
            const errorMessages = document.querySelectorAll('.error-message');

            errorMessages.forEach((errorMessage) => {
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            });
        }
        window.onload = () => {
            hideErrorMessages();
        };

        function handleInputFocusAndValue(input, label) {
            input.addEventListener('focus', function() {
                label.style.top = '-5px';
            });

            input.addEventListener('blur', function() {
                if (input.value.trim() === '') {
                    label.style.top = '50%';
                } else {
                    label.style.top = '-5px';
                }
            });

            // Handle initial input value
            if (input.value.trim() !== '') {
                label.style.top = '-5px';
            }
        }

        // Function to initialize input focus handlers for each input field
        function initializeInputFocusAndValueHandlers(inputId, labelId) {
            const input = document.getElementById(inputId);
            const label = document.querySelector(`label[for="${inputId}"]`);

            if (input && label) {
                handleInputFocusAndValue(input, label);
            }
        }

        // Initialize input focus handlers for each input field
        initializeInputFocusAndValueHandlers('firstname');
        initializeInputFocusAndValueHandlers('middlename');
        initializeInputFocusAndValueHandlers('lastname');
        initializeInputFocusAndValueHandlers('countrycode');
        initializeInputFocusAndValueHandlers('nationality');
        initializeInputFocusAndValueHandlers('email');
        initializeInputFocusAndValueHandlers('password');
        initializeInputFocusAndValueHandlers('phone');
        initializeInputFocusAndValueHandlers('confirmpassword');
        initializeInputFocusAndValueHandlers('referral');
        const countryDropdownItems = document.querySelectorAll('#countryDropdown a');
        countryDropdownItems.forEach(function(item) {
            item.addEventListener('click', function() {
                const countryCodeInput = document.getElementById('countrycode');
                const countryCodeLabel = document.querySelector('label[for="countrycode"]');
                countryCodeLabel.style.top = '-5px';
                countryCodeInput.value = item.textContent;
            });
        });

        // Event listener for nationality dropdown item clicks
        const nationalityDropdownItems = document.querySelectorAll('#nationalityDropdown a');
        nationalityDropdownItems.forEach(function(item) {
            item.addEventListener('click', function() {
                const nationalityInput = document.getElementById('nationality');
                const nationalityLabel = document.querySelector('label[for="nationality"]');
                nationalityLabel.style.top = '-5px';
                nationalityInput.value = item.textContent;
            });
        });

        function validateForm() {
            // Get form input values
            let firstName = document.getElementById("firstname").value;
            let middleName = document.getElementById("middlename").value;
            let lastName = document.getElementById("lastname").value;
            let countryCode = document.getElementById("countrycode").value;
            let nationality = document.getElementById("nationality").value;
            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirmpassword").value;
            let phoneNumber = document.getElementById("phone").value;

            // Regular expressions for validation
            let nameRegex = /^[a-zA-Z]+$/;
            let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            let passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            let phoneNumberRegex = /^\d{10}$/;

            // Validation flags
            let valid = true;

            // Clear any previous error messages
            document.getElementById("firstNameError").innerHTML = "";
            document.getElementById("middleNameError").innerHTML = "";
            document.getElementById("lastNameError").innerHTML = "";
            document.getElementById("countryCodeError").innerHTML = "";
            document.getElementById("nationalityError").innerHTML = "";
            document.getElementById("emailError").innerHTML = "";
            document.getElementById("passwordError").innerHTML = "";

            // First Name validation
            if (!nameRegex.test(firstName)) {
                document.getElementById("firstNameError").innerHTML = "Please enter a valid first name.";
                valid = false;
            }

            // Middle Name validation
            if (middleName !== "" || !nameRegex.test(middleName)) {
                document.getElementById("middleNameError").innerHTML = "Please enter a valid middle name.";
                valid = false;
            }

            // Last Name validation
            if (!nameRegex.test(lastName)) {
                document.getElementById("lastNameError").innerHTML = "Please enter a valid last name.";
                valid = false;
            }
      // Phone Number validation
            if (!phoneNumberRegex.test(phone)) {
                document.getElementById("phoneError").innerHTML = "Please enter a valid phone number";
                valid = false;
            }
            // Country Code and Nationality validation (dropdown values)
            let validCountry = false;
            let validNationality = false;

            if (countryCode === "") {
                document.getElementById("countryCodeError").innerHTML = "Please select a valid Country Code from the dropdown.";
                valid = false;
            } else if (nationality === "") {
                document.getElementById("nationalityError").innerHTML = "Please select a valid Country Code from the dropdown.";
                valid = false;
            } else {
                // Check if the selected country code exists in the dropdown
                let countryDropdown = document.getElementById("countryDropdown");
                let countryOptions = countryDropdown.getElementsByTagName("a");
                for (let i = 0; i < countryOptions.length; i++) {
                    if (countryCode === countryOptions[i].textContent) {
                        validCountry = true;
                        break;
                    }
                }

                // Check if the selected nationality exists in the dropdown
                let nationalityDropdown = document.getElementById("nationalityDropdown");
                let nationalityOptions = nationalityDropdown.getElementsByTagName("a");
                for (let i = 0; i < nationalityOptions.length; i++) {
                    if (nationality === nationalityOptions[i].textContent) {
                        validNationality = true;
                        break;
                    }
                }

                if (!validCountry) {
                    document.getElementById("countryCodeError").innerHTML = "Please select a valid Country Code from the dropdown.";
                    valid = false;
                }

                if (!validNationality) {
                    document.getElementById("nationalityError").innerHTML = "Please select a valid Nationality from the dropdown.";
                    valid = false;
                }
            }

            // Email validation
            if (!emailRegex.test(email)) {
                document.getElementById("emailError").innerHTML = "Please enter a valid email address.";
                valid = false;
            }

            // Password validation
            if (!passwordRegex.test(password)) {
                document.getElementById("passwordError").innerHTML = "Password should contain at least 1 uppercase letter, 1 number, 1 special character, and should be at least 8 characters long.";
                valid = false;
            }
            //Confirm password validation
            if (password !== confirmPassword) {
                document.getElementById("confirmPasswordError").innerHTML = "Passwords do not match.";
                valid = false;
            }


            // If all validations pass, return true to submit the form; otherwise, return false
            if (valid) {
                return true;
            } else {
                event.preventDefault(); // Prevent the form from submitting
                return false;
            }
        }


        // JavaScript code
        function showFullCountryList() {
            let countryDropdown = document.getElementById("countryDropdown");
            countryDropdown.style.display = "block";
            setTimeout(() => {
                countryDropdown.style.opacity = "1";
            }, 20)
        }

        function showFullNationalityList() {
            let nationalityDropdown = document.getElementById("nationalityDropdown");
            nationalityDropdown.style.display = "block";
            setTimeout(() => {
                nationalityDropdown.style.opacity = "1";
            }, 20);
        }


        function filterCountries() {
            let input = document.getElementById("countrycode");
            let countryDropdown = document.getElementById("countryDropdown");
            let filter = input.value.toLowerCase();

            let options = countryDropdown.getElementsByTagName("a");

            for (let i = 0; i < options.length; i++) {
                let option = options[i];
                let country = option.textContent.toLowerCase();

                if (country.indexOf(filter) > -1) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            }
        }

        function filterNationalities() {
            let input = document.getElementById("nationality");
            let nationalityDropdown = document.getElementById("nationalityDropdown");
            let filter = input.value.toLowerCase();

            let options = nationalityDropdown.getElementsByTagName("a");

            for (let i = 0; i < options.length; i++) {
                let option = options[i];
                let country = option.textContent.toLowerCase();

                if (country.indexOf(filter) > -1) {
                    option.style.display = "block";
                } else {
                    option.style.display = "none";
                }
            }
        }

        // Event listener to close the dropdown when clicking outside the input field
        document.addEventListener("click", function(e) {
            let countryInput = document.getElementById("countrycode");
            let nationalityInput = document.getElementById("nationality");
            let countryDropdown = document.getElementById("countryDropdown");
            let nationalityDropdown = document.getElementById("nationalityDropdown");

            if (e.target !== countryInput && e.target !== countryDropdown) {
                countryDropdown.style.opacity = "0";
                setTimeout(() => {
                    countryDropdown.style.display = "none";
                }, 100)
            }
            if (e.target !== nationalityInput && e.target !== nationalityDropdown) {
                nationalityDropdown.style.opacity = "0";
                setTimeout(() => {
                    nationalityDropdown.style.display = "none";
                }, 100);
            }
        });

        // Event listener to set the selected country when clicking on a dropdown item
        let countryDropdown = document.getElementById("countryDropdown");
        let nationalityDropdown = document.getElementById("nationalityDropdown");
        countryDropdown.addEventListener("click", function(e) {
            if (e.target.tagName === "A") {
                document.getElementById("countrycode").value = e.target.textContent;
                countryDropdown.style.display = "none";
            }
        });
        nationalityDropdown.addEventListener("click", function(e) {
            if (e.target.tagName === "A") {
                document.getElementById("nationality").value = e.target.textContent;
                nationalityDropdown.style.display = "none";
            }
        });
    </script>
    <script>
        function initializeDropdown(dropdownInputId, dropdownContentId, errorId) {
            let highlightedOptionIndex = -1;
            let visibleOptionsArray = [];

            const dropdownInput = document.getElementById(dropdownInputId);
            const dropdownContent = document.getElementById(dropdownContentId);
            const errorElement = document.getElementById(errorId);

            function updateVisibleOptionsArray() {
                const options = dropdownContent.getElementsByTagName("a");
                visibleOptionsArray = Array.from(options).filter(
                    (option) => option.style.display !== "none"
                );

                if (highlightedOptionIndex >= visibleOptionsArray.length) {
                    highlightedOptionIndex = visibleOptionsArray.length - 1;
                }
            }

            function updateHighlightedOption() {
                const options = dropdownContent.getElementsByTagName("a");

                for (let i = 0; i < options.length; i++) {
                    options[i].classList.remove("highlighted");
                }

                if (highlightedOptionIndex >= 0 && highlightedOptionIndex < visibleOptionsArray.length) {
                    const selectedOption = visibleOptionsArray[highlightedOptionIndex];
                    selectedOption.classList.add("highlighted");
                }
            }

            function selectHighlightedOption() {
                if (highlightedOptionIndex >= 0 && highlightedOptionIndex < visibleOptionsArray.length) {
                    const selectedOption = visibleOptionsArray[highlightedOptionIndex];
                    dropdownInput.value = selectedOption.textContent;
                    dropdownContent.style.display = "none";
                    highlightedOptionIndex = -1;
                    updateHighlightedOption();
                }
            }

            function navigateDropdown(event) {
                updateVisibleOptionsArray();

                if (event.key === "ArrowUp") {
                    if (highlightedOptionIndex > 0) {
                        highlightedOptionIndex--;
                    } else {
                        highlightedOptionIndex = visibleOptionsArray.length - 1;
                    }
                    updateHighlightedOption();
                    event.preventDefault();
                } else if (event.key === "ArrowDown") {
                    if (highlightedOptionIndex < visibleOptionsArray.length - 1) {
                        highlightedOptionIndex++;
                    } else {
                        highlightedOptionIndex = 0;
                    }
                    updateHighlightedOption();
                    event.preventDefault();
                } else if (event.key === "Enter") {
                    if (highlightedOptionIndex === -1) {
                        highlightedOptionIndex = 0;
                    }
                    selectHighlightedOption();
                    event.preventDefault();
                }
            }

            dropdownInput.addEventListener("keydown", function(event) {
                if (dropdownContent.style.display === "block") {
                    navigateDropdown(event);
                }
            });

            dropdownInput.addEventListener("input", function() {
                filterDropdownOptions();
                highlightedOptionIndex = -1;
                updateHighlightedOption();
            });

            function filterDropdownOptions() {
                const filter = dropdownInput.value.toLowerCase();
                const options = dropdownContent.getElementsByTagName("a");

                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    const optionText = option.textContent.toLowerCase();

                    if (optionText.indexOf(filter) > -1) {
                        option.style.display = "block";
                    } else {
                        option.style.display = "none";
                    }
                }
            }
        }
        // yaa use garney jj lai use garnu cha
        initializeDropdown("countrycode", "countryDropdown", "countryCodeError");
        initializeDropdown("nationality", "nationalityDropdown", "nationalityError");
    </script>



</body>




</html>