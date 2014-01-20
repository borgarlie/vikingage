<html>
<head>
	<title>test</title>
</head>
<body>
	<h1>Hunting</h1>

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

	$mysqli->close();

	?>
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

	<br>
	<?php
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

	<br>
	<form action="?page=hunting" method="post">
	<?php
		// echo "Choose a place to hunt <br>";
		$array = array("place1", "place2", "place3", "place4", "place5");
		$arrayvalues = array("Outside your city", "Nearby forest", "Further away..", "Very far away", "Super hard place");
		for ($i=0; $i < sizeof($array); $i++) { 
			echo "<button name='place' type='submit' style='width: 150px;' value='$array[$i]'>$arrayvalues[$i]</button><br>";
		}
	?>
	</form>

	</center>
</body>
</html>