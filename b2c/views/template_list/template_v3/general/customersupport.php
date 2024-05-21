<?php $val_errors = $this->session->userdata('errors') ? $this->session->userdata('errors') : []; ?>
<?php $emailError = $val_errors['email'] ? $val_errors['email'] : " ";
$nameError = $val_errors['name'] ? $val_errors['name'] : " ";
$messageError = $val_errors['message'] ? $val_errors['message'] : " ";
$phoneError = $val_errors['phone'] ? $val_errors['phone'] : " ";
$resultMessage = $this->session->flashdata("ContactSubmit") ? $this->session->flashdata("ContactSubmit") : "";


$previousData = $this->session->userdata('previousData') ? $this->session->userdata('previousData') : [];
?>

<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>

<body class="contact-body">
	<article class="contact__container">
		<section class="contact__left">

			<div class="image">
				<img src="<?php echo base_url() . IMG_UPLOAD_DIR . "/image.svg"; ?>" alt="" />
			</div>

			<div class="info">
				<span>
					<i class="fa-solid fa-location-dot"></i>Sumangal Residence, Opp. Prime Minister's Quarter, Gate No. 1 Baluwatar, Kathmandu-03
				</span>
				<span> <i class="fa-solid fa-phone"></i>+977-1-5365553/54</span>

				<span>
					<i class="fa-solid fa-envelope"></i>info@travelfreetravels.com
				</span>
				<span>
					<i class="fa-solid fa-globe"></i>www.travelfreetravels.com
				</span>
			</div>
		</section>
		<section class="contact__right">
			<h1 class="contact__title">Send Us A Message!</h1>
			<form action="<?php echo base_url() . "general/contactFormHandle" ?>" method="post" class="form">
				<div class="form-field">
					<i class="fa-solid fa-user icon"></i>
					<input name="name" type="text" placeholder="Your name"
						value="<?php echo isset($previousData['name']) ? $previousData['name'] : ''; ?>" />
					<span class="errorMsg">
						<?php echo $nameError; ?>
					</span>
				</div>

				<div class="form-field">
					<i class="fa-solid fa-envelope icon"></i>
					<input name="email" type="email" placeholder="Your email"
						value="<?php echo isset($previousData['email']) ? $previousData['email'] : ''; ?>" />
					<span class="errorMsg">
						<?php echo $emailError; ?>
					</span>
				</div>
				<div class="form-field">
					<i class="fa-solid fa-phone icon"></i>
					<input name="phone" type="number" placeholder="Your phone number"
						value="<?php echo isset($previousData['phone']) ? $previousData['phone'] : ''; ?>" />
					<span class="errorMsg">
						<?php echo $phoneError; ?>
					</span>
				</div>
				<div class="form-field">
					<i class="fa-solid fa-message icon"></i>
					<textarea name="message" placeholder="Leave us your message here"
						value="<?php echo isset($previousData['message']) ? $previousData['message'] : ''; ?>"></textarea>
					<span class="errorMsg">
						<?php echo $messageError; ?>
					</span>
				</div>
				<button type="submit">
					Send Message <i class="fa-solid fa-paper-plane icon"></i>
				</button>
				<?php $successMessage = $this->session->flashdata("ContactSuccess") ? $this->session->flashdata("ContactSuccess") : false; ?>
				<?php $errorMessage = $this->session->flashdata("ContactFailure") ? $this->session->flashdata("ContactFailure") : false; ?>
				<?php if (!empty($successMessage)): ?>
					<div class="success__container">
						<?php echo $successMessage; ?>
					</div>
				<?php endif; ?>

				<?php if (!empty($errorMessage)): ?>
					<div class="error__container">
						<?php echo $errorMessage; ?>
					</div>
				<?php endif; ?>

			</form>
		</section>
	</article>
</body>

<?php
$this->session->unset_userdata('errors');
$this->session->unset_userdata('previousData'); ?>

<style>
	@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600&display=swap");

	:root {
		--primary: #fff;
		--primary-dark: #8f53a1;
		--primary-light: #91699d;
		--secondary: #0ba0dc;
		--secondary-light: #40a8d1;
		--white: #ffffff;
		--black: #222222;
	}

	.contact-body {
		display: flex;
		align-items: center;
		justify-content: center;
		min-height: 100vh;
		background-color: var(--primary);
	}


	.errorMsg {
		color: red;

	}

	.success__container {
		margin-top: 1em;
		background-color: green;
		color: white;
		min-height: 4em;
		display: flex;
		justify-content: center;
		align-items: center;
		text-align: center;
		font-weight: 900;
		font-size: 14px;
	}

	.error__container {
		margin-top: 1em;
		background-color: red;
		color: white;
		min-height: 4em;
		display: flex;
		justify-content: center;
		align-items: center;
		text-align: center;
		font-weight: 900;
		font-size: 14px;
	}


	.contact__container {
		margin: 300px;
		display: flex;
		justify-content: space-between;
		border-radius: 25px;
		max-width: 1000px;
		overflow: hidden;
		background-color: var(--white);
		box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
	}

	.contact__container .contact__left,
	.contact__container .contact__right {
		flex: 0.5;
		padding: 2rem;
	}

	.contact__left {
		color: var(--white);
		background-color: var(--secondary);
	}

	.contact__left .image {
		width: 100%;
		height: 250px;
		margin-top: 20px;
	}

	.contact__left .image img {
		display: block;
		margin: auto;
		max-width: 100%;
		max-height: 100%;
	}

	.contact__left .info {
		display: flex;
		flex-direction: column;
		padding: 0 2rem;
		margin-top: 15px;
	}

	.info span {
		display: flex;
		align-items: center;
		justify-content: start;
		margin-top: 15px;
		font-size: 14px;
		font-weight: 500;
		cursor: pointer;
	}

	.info span i {
		font-size: 20px;
		height: 40px;
		width: 40px;
		line-height: 40px;
		text-align: center;
		border-radius: 50%;
		margin-right: 10px;
		border: 1px solid var(--white);
	}

	.contact__container .right {
		background-color: var(--white);
	}

	.title {
		font-size: 2rem;
		font-weight: 600;
		margin-bottom: 10px;
	}

	/* 
.subtitle {
  font-size: 1.1rem;
  font-weight: 500;
  margin-bottom: 10px;
} */

	.contact__right .form {
		padding-top: 10px;
	}

	.form .form-field {
		position: relative;
		display: flex;
		flex-direction: column;
		margin-bottom: 20px;
	}

	.form-field .icon {
		position: absolute;
		top: 17px;
		left: 1rem;
		font-size: 18px;
		color: var(--primary-light);
	}

	.form-field input,
	.form-field textarea {
		font-size: 14px;
		font-weight: 500;
		padding: 1rem;
		padding-left: 40px;
		border-radius: 10px;
		outline: none;
		border: 2px solid var(--primary-dark);
		transition: border 0.2s ease;
	}

	.form-field textarea {
		min-height: 13rem;
	}

	.form-field input:focus,
	.form-field input:active,
	.form-field textarea:focus,
	.form-field textarea:active {
		border: 2px solid var(--primary-light);
	}

	/* Chrome, Safari, Edge, Opera */
	.form-field input::-webkit-outer-spin-button,
	.form-field input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	/* Firefox */
	.form-field input[type="number"] {
		-moz-appearance: textfield;
	}

	.form button {
		font-size: 14px;
		font-weight: 500;
		padding: 1rem;
		border-radius: 10px;
		outline: none;
		width: 100%;
		cursor: pointer;
		color: var(--white);
		background-color: var(--primary-dark);
		border: 1px solid var(--primary-dark);
		transition: background-color 0.2s ease, border 0.2s ease;
	}

	.form button:hover {
		background-color: var(--primary-light);
		border: 1px solid var(--primary-light);
	}


	@media(max-width:480px) {

		.contact__container {
			margin: 0;
			padding: 0;
			min-width: 100vw;
			margin-left: 0.3em;
			margin-top: 1em;
			;
		}

		.contact__container .contact__left {
			display: none;
		}

		.contact__container .contact__right {
			background: var(--white);
			min-width: 100vw;
		}







	}

	@media only screen and (min-width: 480px) and (max-width: 768px) {
		.contact__container {
			margin: 0;
			padding: 0;
			min-width: 100vw;
			margin-left: 0.3em;
			margin-top: 1em;
			;
		}

		.contact__container .contact__left {
			display: none;
		}

		.contact__container .contact__right {
			background: var(--white);
			min-width: 100vw;
		}




	}

	@media only screen and (min-width: 768px) and (max-width: 834px) {
		.contact__container {
			margin: 0;
			padding: 0;
			max-width: 80%;
			margin-left: 8rem;
			margin-top: 1em;
		}




	}

	@media only screen and (min-width: 834px) and (max-width:1020px) {
		.contact__container {
			margin: 0;
			padding: 0;
			max-width: 80%;
			margin-left: 8rem;
			margin-top: 1em;
		}


	}

	@media only screen and (min-width:1022px) and (max-width:1185px) {
		.contact__container {
			margin: 0;
			padding: 0;
			max-width: 80%;
			margin-left: 8rem;
			margin-top: 1em;
		}




	}

	@media only screen and (min-width:1185px) {
		.contact__container {
			margin: 0;
			padding: 0;
			max-width: 80%;
			margin-left: 16rem;
			margin-top: 1em;
		}

	}
</style>