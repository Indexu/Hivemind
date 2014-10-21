<?php
	require('resetpass.php');
	require('../includes\variables.php');
?>

<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title>Hivemind - Reset password</title>
	<link rel="stylesheet" href="../css/foundation.css">

	<script src="../js/vendor/custom.modernizr.js"></script>

	<link rel="stylesheet" href="../css/style.css">

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
			<div class="row">
				<div id="resetPassForm" class="panel customPanel">

					<input type="password" name="password" pattern="^.{6,20}$" min="6" max="20" autocomplete="off" placeholder="Password" required>
					<input type="password" name="confirm" pattern="^.{6,20}$" min="6" max="20" autocomplete="off" placeholder="Confirm password" required>				

					<button class="resetPassButton button beeforButton">Reset password</button>
					<div class="resetResult"></div>
				</div>
			</div>
		</div>

	</div>

	<!-- Footer -->
	<?php include($root . '\hopar\GRU_H1\includes\footer.php'); ?>

	<script>
	document.write('<script src=' +
		('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
		'.js><\/script>')
</script>

<script src="../js/foundation.min.js"></script>
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

	<script src="../js/jquery-2.0.2.min.js"></script>
	<script src="../js/script.js"></script>

</body>
</html>
