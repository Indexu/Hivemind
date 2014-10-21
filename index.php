<?php
	require('includes/login.php');
	require('includes\variables.php');
?>

<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title>Hivemind - Log in</title>
	<link rel="stylesheet" href="css/foundation.css">

	<script src="js/vendor/custom.modernizr.js"></script>

	<link rel="stylesheet" href="css/style.css">

	<style type="text/css">
	<!--
	.header{
	background-image: url(http://tsuts.tskoli.is/hopar/gru_h1/hive/img/header/<?php echo $selectedBg; ?>);
	}
	-->
	</style>
</head>
<body>

	<?php
	    include($root . '\hopar\GRU_H1\includes\header.php');
	?>

	<div class="row">

		<div class="large-5 large-centered columns">
			<form id="login">
				<input type="text" name="email" pattern="^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" placeholder="Email" required>
				<input type="password" name="password" pattern="^.{6,20}$" placeholder="Password" required>
		        <label for="rememberme" class="rememberme">
					<input type="checkbox" name="rememberme" id="rememberme" value="on">
					Remember me
				</label>
				<button id="loginBtn" class="medium button">Log In</button>

				<div class="loginResult"></div>
			</form>
			<div class="row">
				<div class="large-6 columns">
					<a id="signupButton" href="register" class="button secondary">Sign up</a>
				</div>
				<div class="large-6 columns">
					<a id="forgotButton" href="" class="button secondary" data-reveal-id="forgotPass">Forgot pass</a>
				</div>
			</div>
			
		</div>

	</div>

	<!-- Footer -->
	<?php include($root . '\hopar\GRU_H1\includes\footer.php'); ?>

	<div id="forgotPass" class="reveal-modal tiny">
	  	<div class="forgotPassForm">
	  		<input type="text" name="forgotEmail" pattern="^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" placeholder="Email" required>
			<button class="medium button forgotPassButton">Send reset link</button>
			<div class="forgotResult"></div>
	  	</div>
	</div>

	<script>
	document.write('<script src=' +
		('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
		'.js><\/script>')
</script>

<script src="js/foundation.min.js"></script>
	  <!--
	  
	  <script src="js/foundation/foundation.js"></script>
	  
	  <script src="js/foundation/foundation.interchange.js"></script>
	  
	  <script src="js/foundation/foundation.abide.js"></script>
	  
	  <script src="js/foundation/foundation.dropdown.js"></script>
	  
	  <script src="js/foundation/foundation.placeholder.js"></script>
	  
	  <script src="js/foundation/foundation.forms.js"></script>
	  
	  <script src="js/foundation/foundation.alerts.js"></script>
	  
	  <script src="js/foundation/foundation.magellan.js"></script>
	  
	  <script src="js/foundation/foundation.reveal.js"></script>
	  
	  <script src="js/foundation/foundation.tooltips.js"></script>
	  
	  <script src="js/foundation/foundation.clearing.js"></script>
	  
	  <script src="js/foundation/foundation.cookie.js"></script>
	  
	  <script src="js/foundation/foundation.joyride.js"></script>
	  
	  <script src="js/foundation/foundation.orbit.js"></script>
	  
	  <script src="js/foundation/foundation.section.js"></script>
	  
	  <script src="js/foundation/foundation.topbar.js"></script>
	  
	-->
	
	<script>
		$(document).foundation();
	</script>

	<script src="js/jquery-2.0.2.min.js"></script>
	<script src="js/script.js"></script>

</body>
</html>
