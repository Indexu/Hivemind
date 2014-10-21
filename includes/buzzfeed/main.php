<?php

$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$root = $_SERVER['DOCUMENT_ROOT'];
require($root . '\hopar\GRU_H1\includes\buzzfeed\getThreads2.php');

$nav = explode("/", $url);
if (substr($nav[(count($nav)-1)], 0, 4) != "?nav") {

	$nav = "b:1";

} else if (!empty($_GET['nav'])) {

	$nav = $_GET['nav'];

}

$nav = explode('#', $nav);

if (count($nav) > 0) {

	$nav = $nav[0];

} 

$nav = explode(':', $nav);

if (empty($nav[1])) { $nav[1] = 0; }
if (empty($nav[2])) { $nav[2] = 0; }

if ($nav[0] != null) {

	renderThreads($nav[0], $nav[1], $nav[2]);

}

/* sort the threads/bumping system
   note : this could be done by having
   a new column in the threads table that 
   gets updated by the timestamp of every new post
   in the thread */

   function sortThreads($threadArray)
   {

   	$sortedThreads = array(

   		"head"  => array(),
   		"reply" => array()

   		);

   	for ($i=0; $i < count($threadArray); $i++) { 

   		$threadID  = $threadArray[$i][1];
   		$index     = $threadArray[$i][4];
   		$timestamp = $threadArray[$i][8];

   		$splitPostDate 	= explode(" ", $timestamp);
   		$splitDate 		= explode("-", $splitPostDate[0]);
   		$splitTime 		= explode(":", $splitPostDate[1]);

		//$timestamp = (/*$splitDate[0] . $splitDate[1] . $splitDate[2]*/ "" . $splitTime[0] . $splitTime[1] . $splitTime[2]);

   		if ($index == 0) {

			// add the first post in every thread to the array
   			array_push($sortedThreads["head"], array(

   				"time" => $timestamp, 

   				"threadID" => $threadID,

   				"index" => $i

   				));

   		} else {

   			if (count($sortedThreads["head"]) != 0) {

   				for ($y=0; $y < count($sortedThreads["head"]); $y++) { 

   					$time = $sortedThreads["head"][$y]["time"];
   					$id   = $sortedThreads["head"][$y]["threadID"];

   					if ($id == $threadID) {

						/* compare the timestamp of the headpost with the replies
						and update it everytime if it's greater */
						if (($time < $timestamp)) {

							$sortedThreads["head"][$y]["time"] = $timestamp;

						}

					}

				}

			}

		}

		array_push($sortedThreads["reply"], array(

			"time" => $timestamp, 

			"threadID" => $threadID,

			"index" => $i

			));

	}

	/* pretty basic sort function, string < string converts
	the string into binary and then compares each index */
	usort($sortedThreads["head"], function($a, $b) {

		return $a['time'] < $b['time'];

	});

	// this is just for an easier time for the render function
	$processData = array();
	$x = 0;
	for ($i=0;$i<count($sortedThreads["head"]);$i++)
	{ 

		$rawData     = $threadArray[($sortedThreads["head"][$i]["index"])];
		$index       = $rawData[4];
		$threadID    = $rawData[1];

        // check if post is OP
		if ($index == 0) {

			$processData[$x] = array();
			$processData[$x][0] = $rawData;

			$y = 1;
			foreach ($sortedThreads["reply"] as $value)
			{

				$rawData = $threadArray[($value["index"])];

				if ($rawData[4] != 0) {

					if ($rawData[1] == $threadID) {

						$processData[$x][$y] = $rawData;
						$y++;

					}

				}

			}

			$x++;

		}

	}

	return $processData;

}

// render out the pages for the threads
function renderPages($length) {

	if ($length > 0) {

		echo "<div class='panel threadNavigation'>";
		$p = [0,1];	

	// 6 threads every page
		if ($length > 6) {

		/* make sure no thread gets left out,
		   we need ceiling here because if we wouldn't it'd
		   work the same as floor and forget to display a page */

		   $pages = ceil($length/6);

		   for ($i=0; $i < $pages; $i++) { 

		   	echo "<a class='navPage'>" . ($i+1) . "</a>";

		   }
		   echo "</div>";

		} else {

			echo "<a class='navPage'>1</a>";

		}

	} else {

		echo "<div class='panel noThreads'>

			<h1 class='noThread'>No threads have been created on this board, be the <a href='#postThread'>first</a> one</h1>

		</div>";

	}

}

function filterComment($comment) 
{

	$postID    = array();
	$greenText = array();
	$links	   = array();
	$spoilers  = array();

	$reconstructed = $comment;
	for ($i=0; $i < strlen($comment); $i++) {
		
		if (substr($comment, $i, 2) == ">>") {

			$id = null;
			for ($j=($i+2); $j < strlen($comment); $j++) { 
				
				$cmt = substr($comment, $j, 1);

				if (!empty($cmt) || ($cmt == "0")) {

					if (!is_numeric($cmt) && ($cmt != '0')) {

						break;

					} else {

						$id = $id . $cmt;

					}

				}
				
			}

			if ($id != null) {

				array_push($postID, $id);

			}

		} else if (substr($comment, $i, 1) == ">" && (substr($comment, $i+1, 1) != ">") && (substr($comment, $i-1, 1) != ">")) {

			$grtext = null;
			for ($j=($i+1); $j < strlen($comment); $j++) { 
				
				$cmt = substr($comment, $j, 1);

				if (!empty($cmt) || ($cmt == "0")) {

					if (($cmt) == "\n") {

						break;

					} else {

						$grtext = $grtext . $cmt;

					}

				}
				
			}
			if ($grtext != null) {

				array_push($greenText, $grtext);

			}

		} else if (substr($comment, $i, 4) == "http") {

			$link = null;
			for ($j=($i+4); $j < strlen($comment); $j++) { 
				
				$cmt = substr($comment, $j, 1);

				if (!empty($cmt) || ($cmt == "0")) {

					if (($cmt) == "\n" || ($cmt) == " ") {

						break;

					} else {

						$link = $link . $cmt;

					}

				}
				
			}
			if ($link != null) {

				array_push($links, $link);

			}

		} else if (substr($comment, $i, 8) == "spoiler(") {

			$spoiler = null;
			for ($j=($i+8); $j < strlen($comment); $j++) { 
				
				$cmt = substr($comment, $j, 1);

				if (!empty($cmt) || ($cmt == "0")) {

					if (($cmt) == ")") {

						break;

					} else {

						$spoiler = $spoiler . $cmt;

					}

				}
				
			}
			if ($spoiler != null) {

				array_push($spoilers, $spoiler);

			}

		}

	}

	foreach ($postID as $ID) {

		$reconstructed = str_replace(

			(">>" . $ID), 
			("<a href='#p_" . $ID . "'>>>" . $ID . "</a>"), 
			$reconstructed

			);

	}

	foreach ($greenText as $Text) {

		$reconstructed = str_replace(

			(">" . $Text), 
			("<font color='#21A31A'>>" . $Text . "</font>"), 
			$reconstructed

			);

	}

	foreach ($links as $link) {

		$reconstructed = str_replace(

			("http" . $link), 
			("<a class='link'href='http" . $link . "'>http" . $link . "</a>"), 
			$reconstructed

			);

	}

	/*foreach ($spoilers as $spoiler) {

		$reconstructed = str_replace(

			("spoiler(" . $spoiler . ")"), 
			("<p class='spoiler'>" . $spoiler . "</p>"), 
			$reconstructed

			);

	}*/

	return $reconstructed;

}

function renderThreads($navBoard, $navPage, $navID)
{

	$rawArray = getThreads($navBoard, $navID);

	$pages = array();
	$pages[0] = array(0,6);
	$pages[1] = array(6,12);
	$pages[2] = array(12,18);
	$pages[3] = array(18,24);
	$pages[4] = array(24,30);
	$pages[5] = array(30,36);

	$indexes = $pages[($navPage-1)];

	$finArray = sortThreads($rawArray);
	$specThread = false;
	$z = 0;

	if ($navID > 0) {

		$indexes = array(0,1);
		$specThread = true;

	} else if (count($finArray) < 6) {

		$indexes = array(0, count($finArray)); 

	}

	for ($i=$indexes[0]; $i < $indexes[1]; $i++) { 

		if ($specThread == true) {

			for ($y=0; $y < count($finArray); $y++) { 
				
				if ($finArray[$y][0][1] == $navID) {

					$j = $y;

				}

			}                                                                                                                                                        

		} else {

			$j = $i;

		}
		
		if (!empty($finArray[$j][0][0])) {

			if ($finArray[$j][0][0] == $navBoard) {

				$board 		= $finArray[$j][0][0];
				$id 		= $finArray[$j][0][1];
				$nick       = $finArray[$j][0][2];
				$postID 	= $finArray[$j][0][3];
				$index 		= $finArray[$j][0][4];
				$comment 	= $finArray[$j][0][5];
				$image 		= $finArray[$j][0][6];
				$status 	= $finArray[$j][0][7];
				$timestamp 	= $finArray[$j][0][8];
				$countPosts = array();
				$countPosts[0] = (count($finArray[$j])-1);
				$countPosts[1] = "reply";

				if ($countPosts[0] > 1) {

					$countPosts[1] = "replies";
					$countPosts[1] = $countPosts[1] . ' ';

				} else if ($countPosts[0] == 0) {

					$countPosts[1] = "no replies";
					$countPosts[0] = "";

				}

				$page = 1;

				if (strlen($comment) > 30) {

					$comment = substr($comment, 0, 30) . '...';

				}

				echo "<div id='tid_" . $id . "' class='threadHeader row'>
				<p class='headerText'>|  
					<a href='?nav=" . $board . ':' . $page . ':' . $id . "' class='openThread' title='Enter thread'>Thread: " . $id . "
						| " . $countPosts[0] . ' ' . $countPosts[1] . " </a>|</p>";

						if ($_SESSION['user']['status'] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) { 
				echo "<a title='Delete this thread' class='deleteThread'>de</a>"; //Delete thread
				echo "<a title='Move this thread' data-dropdown='dropRelocate' class='relocateThread'>de</a>"; //Relocate
				//Relocate - Dropdown
				echo "<ul id='dropRelocate' class='f-dropdown'>
				<li><a class='relocateThreadOption' data-value='b' href=''>/b/</a></li>
				<li><a class='relocateThreadOption' data-value='f' href=''>/f/</a></li>
				<li><a class='relocateThreadOption' data-value='t' href=''>/t/</a></li>
				<li><a class='relocateThreadOption' data-value='am' href=''>/am/</a></li>
				<li><a class='relocateThreadOption' data-value='pol' href=''>/pol/</a></li>
				<li><a class='relocateThreadOption' data-value='mlp' href=''>/mlp/</a></li>
				<li><a class='relocateThreadOption' data-value='lol' href=''>/lol/</a></li>
				<li><a class='relocateThreadOption' data-value='dota' href=''>/dota/</a></li>
				<li><a class='relocateThreadOption' data-value='wg' href=''>/wg/</a></li>
				<li><a class='relocateThreadOption' data-value='wa' href=''>/wa/</a></li>
				<li><a class='relocateThreadOption' data-value='wv' href=''>/wv/</a></li>
				<li><a class='relocateThreadOption' data-value='peta' href=''>/peta/</a></li>
				<li><a class='relocateThreadOption' data-value='tv' href=''>/tv/</a></li>
				</ul>";
				}

		// reply
		echo "<div id='dropReply' class='f-dropdown'>

			<div class='panel postPanel'>

			<form method='post' action='' id='postThread' enctype='multipart/form-data'>
					<input type='file' name='file' id='file'>
					<textarea maxlength='1500' class='comment' name='comment' placeholder=''></textarea>
					<input type='checkbox' name='NSFW' value='1'> NSFW</input>
					<p class='countdown'>1500</p>
					<a class='prefix button'>Post</a>
				</form>

			</div>

		</div>";

		echo "</div><div id='threadID_" . $id . "' class='row'>";

		$tempArray = array();

		if ($specThread == true) {

			$z = count($finArray[$j]);
			$tempArray = $finArray[$j];

		} else {

			$z = 3;
			if (count($finArray[$j]) == 1) {

				$tempArray = array(

					$finArray[$j][0]

					);

			} else if (count($finArray[$j]) == 2) {

				$tempArray = array(

					$finArray[$j][0],
					$finArray[$j][(count($finArray[$j])-1)]

					);

			} else {

				$tempArray = array(

					$finArray[$j][0],
					$finArray[$j][(count($finArray[$j])-2)],
					$finArray[$j][(count($finArray[$j])-1)]

					);

			}

		}

		for ($y=0; $y < $z; $y++) {

			if (!empty($tempArray[$y][0])) {

				$board 		= $tempArray[$y][0];
				$id 		= $tempArray[$y][1];
				$nick       = $tempArray[$y][2];
				$postID 	= $tempArray[$y][3];
				$index 		= $tempArray[$y][4];
				$comment 	= $tempArray[$y][5];
				$image 		= $tempArray[$y][6];
				$status 	= $tempArray[$y][7];
				$timestamp 	= $tempArray[$y][8];
				$colorClass = "";

				if ($status == "beekeeper") { 

					$colorClass = "beekeeper";

				} else if ($status == "queen_bee") {

					$colorClass = "queenbee";

				} else if ($status == "removed") {

					$colorClass = "removed";

				}

				$root = $_SERVER['DOCUMENT_ROOT'];
					/*$pID = "";
					$pIDarray = array();
					for ($k=0; $k < strlen($comment); $k++) { 
						
						if (!empty($comment[$k+1])) {

							if (($comment[$k] . $comment[$k+1]) == ">>") {

								for ($x=($k+2); $x < strlen($comment)-($k+2); $x++) { 
									
									if (!is_numeric($comment[$x])) {

										$pID = substr($comment, ($k), $x);
										$pID = str_replace(">>", "", $pID);
										array_push($pIDarray, $pID);
										break;

									}

								}
 
							}

						}

					}

					foreach ($pIDarray as $pid) {

						$comment = str_replace((">>" . $pid), ("<a href='#p_" . $pid . "'>>>" . $pid . "</a>"), $comment);

					}*/
					
					$comment = filterComment($comment);

					$img = array();
					$img[0] = false;
					$img[1] = "none";

					if (substr($image,0,1) == "t") {

						$img[0] = true;
						$img[1] = $postID . '.' . explode(":", $image)[1];
						$img[2] = $postID . '.jpeg';

					}

					if ($index == 0) {

						if ($img[0] == true) {

							echo "<div id='p_" . $postID . "' class='panel headPost Posts'>

							<div class='row'>
								<p class='status'>" . $timestamp . "</p>
								<a href='#postThread' class='replyButton " . $colorClass . "'>" . $nick . ' Nr.' . $postID . "</a>";
								
									//Report post
								if ($_SESSION['user']['status'] != "beekeeper" && $_SESSION['user']['status'] != "queen_bee" ) {

									echo "<a title='Report this post' data-dropdown='reportDrop' class='reportPost'>de</a>";
								}

								echo "</div>

								<pre><p class='postText'><a target='_blank' href='img/posts/" . $img[1] . "'><img class='postImage' src='img/posts/thumbs/" . $img[2] . "'></a>" . $comment . "</p></pre>";

								if ($_SESSION['user']['status'] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {

									//Delete post
									echo "<div class='row'><a title='Delete this post' class='deletePost'>de</a></div>";
								}

								echo "</div>";

							} else {

								echo "<div id='p_" . $postID . "' class='panel headPost Posts'>

								<div class='row'>
									<p class='status'>" . $timestamp . "</p>
									<a href='#postThread' class='replyButton " . $colorClass . "'>" . $nick . ' Nr.' . $postID . "</a>";

								//Report post
									if ($_SESSION['user']['status'] != "beekeeper" && $_SESSION['user']['status'] != "queen_bee" ) {

										echo "<a title='Report this post' data-dropdown='reportDrop' class='reportPost'>de</a>";
									}

									echo "</div>

									<pre><p class='postText'>" . $comment . "</p></pre>";

									if ($_SESSION['user']['status'] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {

								//Delete post
										echo "<div class='row'><a title='Delete this post' class='deletePost'>de</a></div>";
									}

									echo "</div>";

								}

							} else {

								if ($img[0] == true) {

									echo "<div id='p_" . $postID . "' class='panel replyPost Posts'>

									<div class='row'>
										<p class='status'>" . $timestamp . "</p>
										<a href='#postThread' class='replyButton " . $colorClass . "'>" . $nick . ' Nr.' . $postID . "</a>";

							//Report post
										if ($_SESSION['user']['status'] != "beekeeper" && $_SESSION['user']['status'] != "queen_bee" ) {

											echo "<a title='Report this post' data-dropdown='reportDrop' class='reportPost'>de</a>";
										}

										echo "</div>

										<pre><p class='postText'><a href='img/posts/" . $img[1] . "'><img class='postImage' src='img/posts/thumbs/" . $img[2] . "'></a>" . $comment . "</p></pre>";

										if ($_SESSION['user']['status'] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {

							//Delete post
											echo "<div class='row'><a title='Delete this post' class='deletePost'>de</a></div>";
										}

										echo "</div>";

									} else {

										echo "<div id='p_" . $postID . "' class='panel replyPost Posts'>

										<div class='row'>
											<p class='status'>" . $timestamp . "</p>
											<a href='#postThread' class='replyButton " . $colorClass . "'>" . $nick . ' Nr.' . $postID . "</a>";

						//Report post
											if ($_SESSION['user']['status'] != "beekeeper" && $_SESSION['user']['status'] != "queen_bee" ) {

												echo "<a title='Report this post' data-dropdown='reportDrop' class='reportPost'>de</a>";
											}

											echo "</div>

											<pre><p class='postText'>" . $comment . "</p></pre>";

											if ($_SESSION['user']['status'] == "beekeeper" || $_SESSION['user']['status'] == "queen_bee" ) {

						//Delete post
												echo "<div class='row'><a title='Delete this post' class='deletePost'>de</a></div>";
											}

											echo "</div>";

				//Reporting form
											echo "<form id='reportDrop' class='f-dropdown content' data-dropdown-content>
											<label>Reason: </label>
											<select name='reason'>
												<option value='offtopic'>Off-topic</option>
												<option value='nsfw'>NSWF in a SFW thread</option>
												<option value='incorrectBoard'>Incorrect board</option>
												<option value='advertising'>Advertising</option>
												<option value='bait'>Misleading information</option>
											</select>

											<label>Details (Optional): </label>
											<input type='text' name='details'>

											<label for='reportAgree' class='reportAgree'>
												<input type='checkbox' name='reportAgree' id='reportAgree' value='on' required>
												I agree to be a faggot
											</label>

											<input class='button alert reportButton' type='submit' value='Report'>

										</form>";

									}

								}

							}

						}

					}


					echo "</div>";

				}

			}

			renderPages(count($finArray));

		}

		?>