<?php

//Create adminPanel button
function adminPanel(){
	if ($_SESSION['user']['status'] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) { 
		echo "<h5><a class='button beeforButton' href='beekeeping'>Admin Panel</a></h5>";
	}
}