<?php

	require('beekeeping.php');

?>

<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title>Hivemind - <?php echo $title ?></title>
	<link rel="stylesheet" href="../../css/foundation.css">

	<script src="../../js/vendor/custom.modernizr.js"></script>

	<link rel="stylesheet" href="../../css/style.css">

	<style type="text/css">
	<!--
	.header{
	background-image: url(http://tsuts.tskoli.is/hopar/gru_h1/hive/img/header/<?php echo $selectedBg; ?>);
	}
	-->
	</style>

</head>
<body>

	

		<!--Header-->
		<?php include($root . '\hopar\GRU_H1\includes\header.php'); ?>

	<div class="wrapper">

		<div class="row">
			<div class="large-8 large-centered columns">
				<div class="panel customPanel">
					<h3>Admin Panel</h3>
					<div class="row">
						<div class="large-3 columns">
							<a href="reports"><button class="beeforButton">Reports <?php countReports() ?></button></a>
						</div>

						<div class="large-3 columns">
							<a href="accounts"><button class="beeforButton">Accounts</button></a>
						</div>

						<div class="large-3 columns">
							<button class="beeforButton">Header</button>
						</div>

						<div class="large-3 columns">
							<button class="beeforButton">FAQ</button>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- Footer -->
	<?php include($root . '\hopar\GRU_H1\includes\footer.php'); ?>

	<script>
	document.write('<script src=' +
		('__proto__' in {} ? '../js/vendor/zepto' : '../js/vendor/jquery') +
		'.js><\/script>')
</script>

<script src="../../js/foundation.min.js"></script>
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

	<script src="../../js/jquery-2.0.2.min.js"></script>
	<script src="../../js/script.js"></script>

</body>
</html>

