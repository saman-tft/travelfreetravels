<?php 
$loginError = $GLOBALS['CI']->session->flashdata("loginError") ? $GLOBALS['CI']->session->flashdata("loginError") : " ";

$loginErrorStatus = $loginError === " " ? "hidden" : "block";
$emptyEmail = $GLOBALS['CI']->session->flashdata("emptyEmail") ? $GLOBALS['CI']->session->flashdata("emptyEmail") : " ";
$emptyEmailStatus = $emptyEmail === " " ? "hidden" : "block";
$emptyPassword = $GLOBALS['CI']->session->flashdata("emptyPassword") ? $GLOBALS['CI']->session->flashdata("emptyPassword") : " ";
$emptyPasswordStatus = $emptyPassword === " " ? "hidden" : "block";
$GLOBALS['CI']->load->library('social_network/google');




?>
<?php 
$___favicon_ico = $GLOBALS ['CI']->template->domain_images('favicon/favicon.ico');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="icon" href="<?= $___favicon_ico ?>" type="image/x-icon" />
      <script src="/extras/system/library/javascript/jquery-2.1.1.min.js"></script>
      <script>const app_base_url = "https://www.travelfreetravels.com/index.php" </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,300;1,200;1,500&family=Poppins:wght@100&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        .home-text {
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

        .home-text-small {
            display: none;
            text-align: center;
            background-color: transparent;
            border: none;
            outline: none;
            text-decoration: none;
            box-shadow: 5px 5px 5px purple;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
        }


        body {
            background: linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
            background-size: cover;
            background-repeat: no-repeat;
        }

        main {
            background: url('<?php echo base_url('/images/mascot-main.png')?>');
            background-size: 300px;
            background-repeat: no-repeat;
            animation: gaidaAnimate 20s infinite;
        }

        @keyframes gaidaAnimate {
            0% {
                background-position: top 0px left 0px;
            }

            25% {
                background-position: top 0px right 0;
            }

            50% {
                background-position: bottom 0px right 0px;
            }

            75% {
                background-position: bottom 0px left 0px;
            }

            100% {
                background-position: top 0px left 0px;
            }
        }

        .form__container {
            max-width: 100vw;
            min-height: 100vh;
            display: flex;

            justify-content: center;
        }

        .hidden {
            display: none;
        }

        .block {
            display: block;
        }

        .form__container-top {
            flex: 0.3;
            background: linear-gradient(120deg, purple, #8F53A1, #0BA0DC);
            border: 1px solid #8F53A1;
            margin: auto 0;
            overflow: hidden;
            max-height: 700px;
            border-radius: 30px;
            z-index: 1;
            display: flex;
            flex-direction: column;
            padding: 0;
            padding-bottom: 30px;
            align-items: center;
            box-shadow: 10px 10px 0.7;

        }

        .logo__container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;


        }

        .login__form {
            display: flex;
            flex-direction: column;
            padding: 0 30px;
        }

        label {
            display: block;
            margin-left: 5px;
            font: inherit;
            color: white;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            font: inherit;
            outline: none;

        }


        .login__button-container button {
            width: 100%;
            padding: 10px;
            border: 1px solid #8F53A1;
            border-radius: 10px;
            background-color: #0BA0DC;
            color: white;
            font-size: 18px;
            font-weight: 800;
        }

        .login__button-container button:hover {
            cursor: pointer;
            opacity: 80%;
        }

        .login__forgot-password {
            margin: 1em 0;
        }

        .login__forgot-password a {
            text-decoration: none;
            color: white;
            margin-left: 5px;
        }

        .login__forgot-password:hover {
            text-decoration: underline;
            color: white;
        }

        .login__or {
            margin-top: 20px;
            margin-bottom: 20px;
            color: white;
            font-size: 18px;
            text-align: center;
        }



        .container__error {


            margin-top: 20px;

            border-radius: 20px;
            padding: 5px;
            color: red;
            font-weight: 900;
            font-size: 18px;
            background: rgba(255, 255, 255, 0.3);
        }

        .signup__message {
            color: white;
            margin-top: 1em;
            text-align: center;
            font-size: 18px;
        }

        .signup__message .sign__up-text {
            text-decoration: underline;

        }

        .signup__message .sign__up-text:hover {
            cursor: pointer;
            text-decoration: underline;
            color: white;
            font-weight: 700;

        }

        #g-signin-btn {
            display: none;
        }


        @keyframes animate {
            0% {
                background-position-x: 0px;

            }

            100% {
                background-position-x: 780px;
            }
        }


        @media (max-width: 280px) {
             .home-text-small {
                display: block;
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                padding: 15px 50px;
                border: 1px solid purple;
                border-style: groove;
                font-size:12px;
            }
            .home-text{
                display:none;
            }

                      main{
                animation:none;
                background:transparent;
            }
            .form__container-top {
                flex: 0.6;
                background:url(''<?php echo base_url('/images/mascot-light.png')?>'');
                background-position: top 54px right 54px;
                background-size:300px;
                background-repeat:no-repeat;
            }

            .form__container-top {
                margin: 0;
                min-width: 100vw;

            }

            .login__form {
                padding: 0;
                margin: 0;
            }

            input {
                margin: 0 40px;
                width: 200px;
            }

            .login__button-container button {
                width: 60%;
                margin-left: 50px;
            }

            button {
                margin: 0 40px;
                width: 150px;
            }

            label {
                margin-left: 40px;
            }

            .login__forgot-password {
                margin: 1em 3em;


            }

            .signup__message {
                color: white;
                margin-top: 1em;
                text-align: center;
                font-size: 12px;
            }

            #g-signup-btn {
                display: none;
            }

        }

        @media only screen and (min-width: 281px) and (max-width: 480px) {
                   .home-text-small {
                display: block;
                position: fixed;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                padding: 15px 50px;
                border: 1px solid purple;
                border-style: groove;
                font-size:12px;
            }
            .home-text{
                display:none;
            }

                      main{
                animation:none;
                background:transparent;
            }
            .form__container-top {
                flex: 0.6;
         background:url(''<?php echo base_url('/images/mascot-light.png')?>'');
                background-position: top 54px right 54px;
                background-size:300px;
                background-repeat:no-repeat;
            }

            .form__container-top {
                margin: 0;
                min-width: 100vw;
            }



        }

        @media only screen and (min-width: 480px) and (max-width: 768px) {

          main{
                animation:none;
                background:transparent;
            }
            .form__container-top {
                flex: 0.6;
              background:url(''<?php echo base_url('/images/mascot-light.png')?>'');
                background-position: center;
                background-size:300px;
                background-repeat:no-repeat;
            }
            .form__container {
                padding: 0;
                justify-content: center;

            }

            .form__container-top {
                flex: 0.8;
            }

        }

        @media only screen and (min-width: 768px) and (max-width: 834px) {
            main{
                animation:none;
                background:transparent;
            }
            .form__container-top {
                flex: 0.6;
 background:url(''<?php echo base_url('/images/mascot-light.png')?>'');
                background-position: center;
                background-size:300px;
                background-repeat:no-repeat;
            }
            .login__info {
                font-size: 25px;
            }

            .form__container-top {
                flex: 0.6;
            }

            .form__container {
                padding: 0;
                justify-content: center;

            }


        }

        @media only screen and (min-width: 834px) and (max-width:1020px) {
            main{
                animation:none;
                background:transparent;
            }
            .form__container-top {
                flex: 0.6;
 background:url(''<?php echo base_url('/images/mascot-light.png')?>'');
                background-position: center;
                background-size:300px;
                background-repeat:no-repeat;
            }

            .form__container {
                padding: 0;
                justify-content: center;


            }

            .login__info {
                font-size: 28px;
            }


        }

        @media only screen and (min-width:1022px) and (max-width:1185px) {
            .form__container-top {
                flex: 0.4;
            }

            .form__container {
                padding: 0;
                justify-content: center;
            }

            .login__info {
                font-size: 28px;
            }


        }

        @media only screen and (min-width:1185px) {
            .form__container-top {
                flex: 0.3;
            }

            .form__container {
                padding: 0;
                justify-content: center;
            }

            .login__info {
                font-size: 18px;
            }




        }
    </style>
</head>

<body>
    <header>
        <a href="<?php echo base_url('/');?>" class="home-text">Back To Home</a>
    </header>
    <main>
        <div class="form__container">
            <div class="form__container-top">

               <a href="<?php echo base_url('/');?>"> <img src="<?php echo base_url('/images/companylogo.png')?>" height="140px" width="250px" alt="Travel Free Travels" loading="lazy"> </a>

                <div class="login__form" id="login-form">
                    <form action="<?php echo base_url('/loginhandle'); ?>" method="POST" onsubmit="return validateForm();">

                        <label for="email">Email:</label>

                        <input type="email" name="email" id="email" required>
                        <span class="container__error <?php echo $emptyEmailStatus; ?> error-message"><?php echo $emptyEmail; ?></span>
                        <span class="" style="color:red;text-align:center" id="emailError"></span>

                        <br> <br>


                        <label for="password">Password:</label>

                        <input type="password" name="password" id="password" required>
                        <span class="container__error <?php echo $emptyPasswordStatus; ?> error-message"><?php echo $emptyPassword; ?></span>

                        <p class="login__forgot-password"><a href="<?php echo base_url('/forgotload')?>">Forgot your password?</a></p>

                        <div class="login__button-container">
                            <button type="submit">Login</button>
                        </div>

                    </form>

                    <div class="container__error <?php echo $loginErrorStatus; ?> error-message">

                        <?php echo $loginError; ?>
                    </div>

                    <div class="login__or">OR</div>

                    <div class="google__container">
                        <?php
                        echo $GLOBALS['CI']->google->load_library();
                        echo $GLOBALS['CI']->google->login_button(); ?>
                    </div>
                    <div class="signup__message">
                        Not a member yet?&nbsp;&nbsp;<a style="color:white;" href="<?php echo base_url('/register')?>" class="sign__up-text">Create an account</a>
                    </div>
                </div>

                    <a href="<?php echo base_url('/');?>" class="home-text-small">Back To Home</a>  

            </div>
        </div>
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

        function validateForm() {
            // Get form input values

            let email = document.getElementById("email").value;


            // Regular expressions for validation

            let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            // Validation flags
            let valid = true;



            document.getElementById("emailError").innerHTML = "";







            // Email validation
            if (!emailRegex.test(email)) {
                document.getElementById("emailError").innerHTML = "Please enter a valid email address.";
                valid = false;
                setTimeout(() => {
                    document.getElementById("emailError").style.display = 'none';
                }, 5000);
            }



            // If all validations pass, return true to submit the form; otherwise, return false
            if (valid) {
                return true;
            } else {
                event.preventDefault(); // Prevent the form from submitting
                return false;
            }
        }
    </script>



</body>




</html>