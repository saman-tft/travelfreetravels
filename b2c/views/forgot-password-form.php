<?php 
$___favicon_ico = $GLOBALS ['CI']->template->domain_images('favicon/favicon.ico');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="<?= $___favicon_ico ?>" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
        
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,300;1,200;1,500&family=Poppins:wght@100&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
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

        }

        .logo__container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;


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




        .error-message {


            margin-top: 20px;
            padding: 5px;
            color: red;
            font-weight: 900;
            font-size: 18px;

        }



        .forgot__password-form {
            display: flex;
            flex-direction: column;
            padding: 0 30px;
        }

        .forgot__password-heading {
            text-align: center;
            color: white;
            margin-bottom: 1em;
            font-family: monospace;
            font-weight: 900;
            font-size: 28px;
        }

        .forget__password-button-container button {
            margin-top: 1em;
            width: 100%;
            padding: 10px;
            border: 1px solid #8F53A1;
            border-radius: 10px;
            background-color: #0BA0DC;
            color: white;
            font-size: 18px;
            font-weight: 800;
        }

        .forget__password-button-container button:hover {
            cursor: pointer;
            opacity: 80%;
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
            main{
                background:none;
            }

            .form__container-top {
                margin: 0;
                min-width: 100vw;
                                background:url('<?php echo base_url('/images/mascot-light.png')?>');
                background-position: top 54px right 54px;
                background-size:300px;
                background-repeat:no-repeat;

            }
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





        }

        @media only screen and (min-width: 281px) and (max-width: 480px) {
                        main{
                background:none;
            }

            .form__container-top {
                margin: 0;
                min-width: 100vw;
                                background:url('<?php echo base_url('/images/mascot-light.png')?>');
                background-position: top 54px right 54px;
                background-size:300px;
                background-repeat:no-repeat;
            }
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





        }

        @media only screen and (min-width: 480px) and (max-width: 768px) {


            .form__container {
                padding: 0;
                justify-content: center;

            }

            .form__container-top {
                flex: 0.8;
            }






        }

        @media only screen and (min-width: 768px) and (max-width: 834px) {
            .login__info {
                font-size: 25px;
            }

            .form__container-top {
                flex: 0.6;
            }



        }

        @media only screen and (min-width: 834px) and (max-width:1020px) {
            .form__container-top {
                flex: 0.6;
            }

            .form__container {
                padding: 0;
                justify-content: center;


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



        }

        @media only screen and (min-width:1185px) {
            .form__container-top {
                flex: 0.3;
            }

            .form__container {
                padding: 0;
                justify-content: center;
            }




        }
    </style>
</head>

<body>
        <header>
        <a href="<?php echo base_url('/login');?>" class="home-text">Login</a>
    </header>
    <main>
        <div class="form__container">
            <div class="form__container-top">

              <a class="logo" href="<?php echo base_url() ?>"> <img src="<?php echo base_url('/images/companylogo.png')?>" height="140px" width="250px" alt="Travel Free Travels" loading="lazy"> </a>
                <div class="forgot__password-form" id="forgot-password-form">
                    <h2 class="forgot__password-heading">Password Reset</h2>

                    <form action="<?php echo base_url('/forgot'); ?>" method="POST" onsubmit="return validateForm();">
                        <label for="email">Please enter the email of your account.</label>
                        <br>

                        <input type="email" name="email" id="email" placeholder="Your Email" required>
                        <span class="error-message" id="emailError"><?php echo $data; ?></span>
                        <div class="forget__password-button-container">
                            <button type="submit">Submit</button>
                        </div>

                        <br> <br>
                        
                    <a href="<?php echo base_url('/login');?>" class="home-text-small">Login</a> 
                    </form>
                </div>


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