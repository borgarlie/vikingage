<html>
<head>
	<style type="text/css">
	  	#ttt {
	    	position: absolute;
	    	opacity: 0;
	    	z-index: 1;
	    }

	    #map img {
	    	position: absolute;
	    	top: 0;
	    	left: 0;
	    	right: 0;
	    }

	    #map {
		    position: relative;
		    top: 0px;
		    left:0px;
		    right: 0px;
		    width: 100%;
		    height: 440px;
		}

		#map button {
			height: 100%;
			width: 100%;
			max-width: 100%;
			max-height: 100%;
			min-height: 100%;
		}
		#map button:hover {
		  	cursor: pointer;
		}
 	</style>
</head>
<body>

	<?php

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	$resourceshopping = $mysqli->prepare("SELECT exp, food, hunters, dogs, hunterslevel, dogslevel, lasthunt FROM users WHERE user_name = '" . $_SESSION['user_name'] . "';");
	$resourceshopping->execute();
    $resourceshopping->bind_result($exp, $food, $hunters, $dogs, $hunterslevel, $dogslevel, $lasthunt);
    $resourceshopping->fetch();
    $resourceshopping->close();

    // dogslevel = tracking skills
    // hunterslevel = hunting skills
    $trackingskill = $dogslevel + 1;
    $tracking = $dogs * $trackingskill;
    $hunterskill = $hunterslevel + 2;
    $huntdamage = $dogs + ($hunters * $hunterskill); // + 2 ? to get multiplier for more damage or + 1 ?
    $constdmg = $huntdamage;
    $consttrack = $tracking;
    $timespent = time() - $lasthunt;
    $timeleft = 300 - $timespent;

    if (isset($_POST['place'])) {
		if ($timespent >= 300) {

			$place = $_POST['place'];
			$newexp = $exp + 20;
			$extrachance = rand(1,100);
			$chance = rand(0, 3);
			$chance2 = rand(1, 2);

			if ($extrachance > 50) {
				$chance = rand(1,2);
			} // maybe change to $chance = 1;

			$huntdamage *= $chance;
			$tracking  *= $chance2;

			// echo "Your damage: $constdmg * $chance <br>";
			// echo "Your tracking: $consttrack * $chance2 <br>";

			if ($place == "place1") {

				$enemydamage = 10;
				$enemyhide = 3;

				if ($tracking >= $enemyhide) {

					if ($huntdamage > $enemydamage) {
						$newexp += 20;
						$plussfood = rand(40,100);
						$newfood = $food + $plussfood;
						$newhunters = $hunters; // remove this one... use it on later "hunting areas"
						$newdogs = $dogs;
						$updatetime = time();
						$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, food = ?, hunters = ?, dogs = ?, lasthunt = ? WHERE user_name = ?");
						$updateitems->bind_param('iiiiis', $newexp, $newfood, $newhunters, $newdogs, $updatetime, $_SESSION['user_name']);
						$updateitems->execute();
						$updateitems->close();
						$timeleft = 300;
						$timespent = 0;
						echo "You killed your target!<br>You earned $plussfood food.";
					}
					else {
						$loosechance = rand(1,100);
						$updatetime = time();
						if ($dogs - 1 >= 0 && $loosechance > 75) { // 25% chance to loose a dog if u loose the fight.
							$newdogs = $dogs - 1;
							echo "You were too weak, and lost a dog in your fight.";
							$dogs = $newdogs;
						}
						else {
							$newdogs = $dogs;
							echo "You were too weak, and lost the fight.";
						}
						$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, dogs = ?, lasthunt = ? WHERE user_name = ?");
						$updateitems->bind_param('iiis', $newexp, $newdogs, $updatetime, $_SESSION['user_name']);
						$updateitems->execute();
						$updateitems->close();
						$timeleft = 300;
						$timespent = 0;
					}

				}
				else {
					$updatetime = time();
					$updateitems = $mysqli->prepare("UPDATE users SET exp = ?, lasthunt = ? WHERE user_name = ?");
					$updateitems->bind_param('iis', $newexp, $updatetime, $_SESSION['user_name']);
					$updateitems->execute();
					$updateitems->close();
					$timeleft = 300;
					$timespent = 0;
					echo "You couldn't find anything to kill";
				}

			}
			else if ($place == "place2") {
				// if (win) {
				// $newexp += 5;
				// }
				echo "Not created yet.";

			}
			else if ($place == "place3") {
				echo "Not created yet.";

			}
			else if ($place == "place4") {
				echo "Not created yet.";

			}
			else if ($place == "place5") {
				echo "Not created yet.";

			}
	    }
	    else {
	    	echo "You need to wait 5 minutes between each hunting session!<br>";
	    }
	}
	else {
		echo "<br>";
	}

	$mysqli->close();

	if ($timespent >= 300) {
		echo "<br>Choose a place to hunt";
	}
	else {
		?>
		<span id="countdown" class="timer"></span>
		<script>
			var seconds = <?php echo "$timeleft"; ?>;
			var minutes = Math.round((seconds - 30)/60);
			var remainingSeconds = seconds % 60;
			if (remainingSeconds < 10) {
		        remainingSeconds = "0" + remainingSeconds; 
		    }
			document.getElementById('countdown').innerHTML = "Cooldown: " + minutes + ":" + remainingSeconds;
			function secondPassed() {
		    	if (seconds == 0) {
		        	clearInterval(countdownTimer);
		        	document.getElementById('countdown').innerHTML = "Choose a place to hunt";
		    	} else {
		        	seconds--;
		        	var minutes = Math.round((seconds - 30)/60);
			    	var remainingSeconds = seconds % 60;
			    	if (remainingSeconds < 10) {
			        remainingSeconds = "0" + remainingSeconds; 
			    	}
			    	document.getElementById('countdown').innerHTML = "Cooldown: " + minutes + ":" + remainingSeconds;
		    	}
			}
			var countdownTimer = setInterval('secondPassed()', 1000);
		</script>
		<?php
	}
	?>

	<div id="map">

		<table id="ttt" height = "100%" width = "100%" cellspacing="0" cellpadding="0" border="0">
			<form action="?page=hunting" method="post">
			<tr height = "32%">
				<td alight="center" bgcolor="#FF0000"></td>
			</tr>
			<tr height = "30%">
				<td width="23%" align="center" bgcolor="#111111"></td>
				<td width="12%" align="center" bgcolor="#aa0000">
					<button name='place' type='submit' value='place1'></button>
				</td>
				<td width="2%" align="center" bgcolor="#550000"></td>
				<td width="17%" align="center" bgcolor="#550055">
					<button name='place' type='submit' value='place2'></button>
				</td>
				<td width="19%" align="center" bgcolor="#555555"></td>
				<td width="16%" align="center" bgcolor="#555500">
					<button name='place' type='submit' value='place3'></button>
				</td>
				<td width="22%" align="center" bgcolor="#005500"></td>
			</tr>
			<tr>
			  	<td width="23%" align="center" bgcolor="#331111"></td>
				<td width="12%" align="center" bgcolor="#aa3300"></td>
				<td width="2%" align="center" bgcolor="#553300"></td>
				<td width="17%" align="center" bgcolor="#5f00ff"></td>
				<td width="19%" align="center" bgcolor="#ff55ff">
					<!-- here -->
					<table width="100%" height="100%">
						<tr height="70%">
							<td width="70%" bgcolor="ff00ff">
								<button name='place' type='submit' value='place5'></button>
							</td>
							<td width="30%" bgcolor="ff00ff">
							</td>
						</tr>
						<tr height="30%">
							<td width="50%" bgcolor="ff00ff">
							</td>
							<td width="50%" bgcolor="ff00ff">
							</td>
						</tr>
					</table>
				</td>
				<td width="16%" align="center" bgcolor="#55ff00">
					<!-- here -->
					<table width="100%" height="100%">
						<tr height="20%">
							<td width="100%" bgcolor="ff00ff">
							</td>
						</tr>
						<tr height="60%">
							<td width="100%" bgcolor="ff00ff">
								<button name='place' type='submit' value='place4'></button>
							</td>
						</tr>
						<tr height="20%">
							<td width="100%" bgcolor="ff00ff">
							</td>
						</tr>
					</table>
				</td>
				<td width="22%" align="center" bgcolor="#a055a2"></td>
			</tr>
			</form>
		</table>

		<img border="0" src="pictures/map5.png" alt="hunting map" width="100%" height="100%">

	</div>

	<center>
	<table  border="0" cellspacing="15">
		<tr> 
			<td style = "text-align: left;">
				Hunters:
			</td>
			<td style = "text-align: center;">
				<?php
				echo "$hunters";
				?>
			</td>
			<td style = "text-align: center;">
				<?php
				echo "Damage: $hunterskill";
				?>
			</td>
		</tr>
		<tr> 
			<td style = "text-align: left;">
				Dogs:
			</td>
			<td style = "text-align: center;">
				<?php
				echo "$dogs";
				?>
			</td>
			<td style = "text-align: center;">
				<?php
				echo "Damage: 1";
				?>
			</td>
			<td style = "text-align: center;">
				<?php
				echo "Tracking skill: $trackingskill";
				?>
			</td>
		</tr>
		<tr>
			<td style = "text-align: left;">
				Total: 
			</td>
			<td style = "text-align: left;">
			</td>
			<td style = "text-align: center;">
				<?php
				echo "Damage: $constdmg";
				?>
			</td>
			<td style = "text-align: center;">
				<?php
				echo "Tracking skill: $consttrack";
				?>
			</td>
		</tr>
	</table>
	</center>
	<h1></h1>
</body>
</html>