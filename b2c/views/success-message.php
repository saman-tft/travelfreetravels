<?php 
$___favicon_ico = $GLOBALS ['CI']->template->domain_images('favicon/favicon.ico');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
        <link rel="icon" href="<?= $___favicon_ico ?>" type="image/x-icon" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: linear-gradient(120deg, purple, #8f53a1, #0ba0dc);
            background-size: cover;
            background-repeat: no-repeat;
        }

        main {
            min-height: 100vh;
            min-width: 100vw;
            background: url('<?php echo base_url('/images/mascot-main.png')?>');
            background-size: 300px;
            background-repeat: no-repeat;
            animation: gaidaAnimate 20s infinite;

        }

        .main__article {
            display: grid;
            place-content: center;
            place-items: center;
            min-height: 100vh;
            min-width: 100vw;
        }

        .main__article-section {
            max-height: 25em;
            max-width: 45em;
            padding: 20px;
            background: purple;
            border: 10px solid;
            border-color: #0ba0dc #8f53a1 #8f53a1 #0ba0dc;
            border-style: inset;
            box-shadow: 8px 5px 10px;
        }

        .image__container {
            text-align: center;
        }

        .title {
            text-align: center;
            font-family: monospace;
            color: white;
            font-weight: 800;
            font-size: 3em;
            margin-bottom: 0.5em;

        }

        .message {
            display: flex;
            justify-content: flex-end;
            margin: 2em;

        }

        .right {
            max-width: 70%;
            color: white;
            font-family: monospace;
            font-size: 16px;
            text-align: justify;
            position: relative;

        }

        h1 {
            position: relative;
        }

        h1::before {
            position: absolute;
            content: url("data:image/png;base64,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");
            bottom: -50px;
            left: 80px;
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

        @media (max-width: 280px) {
                     main{
                animation:none;
                background:none;
            }
            .main__article {
                padding: 0;
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
                min-width: 80%;
            }
            .home-text{
                display:none;
            }

            body {
                min-height: 100vh;
                min-width: 100vw;

            }

            .message {
                justify-content: center;
                margin: 0;

            }

            .right {
                max-width: 100%;
                padding: 0;
            }

            .main__article {
                display: grid;
                place-content: center;



            }

            .main__article-section {
                max-height: auto;
                max-width: 100vw;
                min-height: 400px;
                padding: 0;
                background: purple;
                border: 10px solid;
                border-color: #0ba0dc #8f53a1 #8f53a1 #0ba0dc;
                border-style: inset;
                box-shadow: 8px 5px 10px;
            }

            h1 {
                margin-top: 1em;
            }

            h1:before {
                display: none;
            }

            .image__container a {
                position: relative;
            }

            .image__container a::before {
                position: absolute;
                content: url("data:image/png;base64,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");
                top: -40px;
                left: 70px;
            }



        }

        @media only screen and (min-width: 281px) and (max-width: 480px) {
               main{
                animation:none;
                background:none;
            }
                         .home-text-small {
                display: block;
                position: fixed;
                top: 20px;
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
            .main__article {
                padding: 0;
            }

            body {
                min-height: 100vh;
                min-width: 100vw;
            }

            .message {
                justify-content: center;
                margin: 0;
            }

            .right {
                max-width: 100%;
            }

            .main__article {
                display: grid;
                grid-template-columns: 1;


            }

            .main__article-section {
                max-height: auto;
                max-width: 100vw;
                min-height: 400px;
                padding: 0;
                background: purple;
                border: 10px solid;
                border-color: #0ba0dc #8f53a1 #8f53a1 #0ba0dc;
                border-style: inset;
                box-shadow: 8px 5px 10px;
            }

            h1 {
                margin-top: 1em;
            }

            h1::before {
                display: none;
            }

            .image__container a {
                position: relative;
            }

            .image__container a::before {
                position: absolute;
                content: url("data:image/png;base64,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");
                top: -40px;
                left: 70px;
            }


        }

        @media only screen and (min-width: 480px) and (max-width: 755px) {
            .main__article {
                padding: 0;
            }

            body {
                min-height: 100vh;
                min-width: 100vw;
            }

            .message {
                justify-content: center;
                margin: 0;
            }

            .right {
                max-width: 100%;
            }

            .main__article {
                display: grid;
                grid-template-columns: 1;


            }

            .main__article-section {
                max-height: auto;
                max-width: 100vw;
                min-height: 400px;
                padding: 0;
                background: purple;
                border: 10px solid;
                border-color: #0ba0dc #8f53a1 #8f53a1 #0ba0dc;
                border-style: inset;
                box-shadow: 8px 5px 10px;
            }

            h1 {
                margin-top: 1em;
            }

            h1::before {
                display: none;
            }

            .image__container a {
                position: relative;
            }

            .image__container a::before {
                position: absolute;
                content: url("data:image/png;base64,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");
                top: -40px;
                left: 70px;
            }
        }
    </style>
</head>

<body>
        <header>
        <a href="<?php echo base_url('/');?>" class="home-text">Back To Home</a>
    </header>
    <main>
        <article class="main__article">
            <section class="main__article-section">
                <div class="image__container">
                    <a href="<?php echo base_url('/'); ?>"><img src="<?php echo base_url('/images/companylogo.png')?>" alt="Travel Free Travels" width="270" /></a>
                </div>
                <div class="message__container">
                    <div class="title">
                        <h1>Success</h1>
                    </div>
                    <div class="message">

                        <div class="right">
                            <p>

                                <?php echo $data; ?>
                            </p>
                             <a href="<?php echo base_url('/');?>" class="home-text-small">Back To Home</a>  
                        </div>

                    </div>
                </div>
            </section>
        </article>

    </main>
</body>

</html>