<?php

	$root = $_SERVER['DOCUMENT_ROOT'];
	require($root . '\hopar\GRU_H1\dbcon\dbcon.php');
	require('userlist.php');

?>

<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width">

    <title>Hivemind - Hive</title>
	<link rel="stylesheet" href="../../css/foundation.css">

	<script src="../../js/vendor/custom.modernizr.js"></script>

	<link rel="stylesheet" href="../../css/style.css">
</head>
<body>

	

		<!--Header-->
		<?php include($root . '\hopar\GRU_H1\includes\header.php'); ?>

	<div class="wrapper">

		<!--Topnav-->
		<?php include($root . '\hopar\GRU_H1\includes\topnav.php'); ?>

		<div class="row">

		    <div class="large-4 columns">
		        <div class="panel">
		            <h4>Userlist</h4>
		            
		            <table>
		            	<thead>
					    	<tr>
							   	<th>ID</th>
							   	<th>Alias</th>
							   	<th>Status</th>
							</tr>
					  </thead>

					  <tbody>
					  	<?php userList() ?>
					  </tbody>

		            </table>

		        </div>
		            
		    </div>
		</div>

	</div>

	<!-- Footer -->
	<?php include($root . '\hopar\GRU_H1\includes\footer.php'); ?>

	<script>
	document.write('<script src=' +
		('__proto__' in {} ? '../../js/vendor/zepto' : '../../js/vendor/jquery') +
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

