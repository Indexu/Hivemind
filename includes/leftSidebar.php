<?php
	require("../includes/functions.php");
?>

<!--UserNav-->
<div class="large-3 columns">
	<div class="panel beeforProfile">
		<a href="#"><?php include('account/beevatar.php') ?></a> <!--Avatar-->
		<h5 class="alias"><a href="#"><?php echo $_SESSION['user']['alias'] ?></a></h5>
		<!--Buttons-->
		<div class="beeformation">
			<h5><a class="button beeforButton" href="profile">Profile</a></h5>
			<h5><a class="button beeforButton" href="account/chitin">Chitin</a></h5>
			<?php adminPanel(); ?>
			<h5><a class="button beeforButton" href="account/logout">Logout</a></h5>
		</div>
	</div>

	<!--Posting-->
	<div class="panel postPanel">

		<form method="post" action='' id="postThread" enctype="multipart/form-data">
			<input type="file" name="file" id="file">
			<textarea maxlength="1500" class="comment" name="comment" placeholder=""></textarea>
			<p class="countdown">1500</p>
			<a class="prefix button">Post</a>
		</form>

	</div>
</div>